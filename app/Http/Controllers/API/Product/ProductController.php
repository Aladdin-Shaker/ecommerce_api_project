<?php

namespace App\Http\Controllers\API\Product;

use Illuminate\Support\Facades\Storage;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Model\File as ModelFile;
use App\Model\OtherData;
use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\ProductMall;
use App\Model\RelatedProduct;
use App\Model\Size;
use App\Model\Weight;
use Illuminate\Support\Facades\File;

use function PHPSTORM_META\type;

class ProductController extends Controller
{

    public function index()
    {
        // return $product->render('admin.products.index', ['title' =>  trans('admin.products')]);
    }

    public function create()
    {
        $product = Product::create(['title' => '']);
        if (!empty($product)) {
            return redirect(aurl('products/' . $product->id . '/edit'));
        }
    }

    // load the sizes and weights according to selected department and his parent
    public function prepare_weight_size()
    {
        if (request()->json() and request()->has('dep_id')) {

            // get the parents of selected department
            $dep_list =  array_diff(explode(',',  get_dep_parent(request('dep_id'))), [request('dep_id')]);
            //return ($dep_list);
            $sizes = Size::where('is_public', 'yes')
                ->whereIn('department_id', $dep_list)
                ->orWhere('department_id', request('dep_id'))
                ->pluck('name_' . session('lang'), 'id');
            $weights = Weight::pluck('name_' . session('lang'), 'id');
            return view('admin.products.ajax.weight_size', [
                'sizes' => $sizes,
                'weights' => $weights,
                'product' => Product::find(request('product_id'))
            ])->render();
        } else {
            return trans('admin.please_select_department');
        }
    }

    public function update(Request $request, $id)
    {
        $data =  $this->validate(request(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'weight' => 'required',
            'size' => 'required',
            'color' => 'sometimes|nullable',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'status' => 'sometimes|nullable|in:pending,refused,active',
            'reason' => 'sometimes|nullable|numeric',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'start_offer_at' => 'sometimes|nullable|date',
            'end_offer_at' => 'sometimes|nullable|date',
            'price_offer' => 'sometimes|nullable|numeric',
            'department_id' => 'required|numeric',
            'trade_id' => 'required|numeric',
            'manu_id' => 'required|numeric',
            'color_id' => 'sometimes|nullable|numeric',
            'size_id' => 'sometimes|nullable|numeric',
            'weight_id' => 'sometimes|nullable|numeric',
            'currency_id' => 'sometimes|nullable|numeric',
        ]);

        if (request()->has('mall')) {
            ProductMall::where('product_id', $id)->delete();
            foreach (request('mall') as $mall) {
                ProductMall::create([
                    'product_id' => $id,
                    'mall_id' => $mall
                ]);
            }
        }

        if (request()->has('related')) {
            RelatedProduct::where('product_id', $id)->delete();
            foreach (request('related') as $related) {
                RelatedProduct::create([
                    'product_id' => $id,
                    'related_product' => $related
                ]);
            }
        }

        if (request()->has('input_key') && request()->has('input_value')) {
            $i = 0;
            $other_data = '';
            OtherData::where('product_id', $id)->delete();
            foreach (request('input_key') as $key) {
                $data_value = !empty(request('input_value')[$i]) ? request('input_value')[$i] : '';
                OtherData::create([
                    'product_id' => $id,
                    'data_key' => $key,
                    'data_value' => $data_value,
                ]);
                $i++;
            }
            $data['other_data'] = rtrim($other_data, '|');
        }

        Product::where('id', $id)->update($data);
        return response(['status' => true, 'message' => trans('admin.record_updated')], 200); // will still in the same page
    }


    // copy product data
    public function product_copy($product_id)
    {
        if (request()->ajax()) {
            $copy = Product::find($product_id)->toArray(); // get product and convert to array
            $copyAsModel = Product::find($product_id); // get product => get model instance for relations
            unset($copy['id']); // delete the old id to generate a new one
            $create =  Product::create($copy); // create a new row

            // copy main image
            if (!empty($copy['photo'])) {
                $ext = File::extension($copy['photo']); // get the extension
                $new_path = 'products/' . $create->id . '/' . md5(rand()) . '.' . $ext; // make new path
                $create->photo = $new_path; // exchange the new path
                Storage::copy($copy['photo'], $new_path); // copy the image
                $create->save();
            }

            // copy other files
            $files = $copyAsModel->files()->get();
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $hashname = md5(rand());
                    $ext = File::extension($file->full_file); // get the extension
                    $new_path = 'products/' . $create->id . '/' . $hashname . '.' . $ext; // make new path
                    Storage::copy($file->full_file, $new_path); // copy the image
                    $add = ModelFile::create([
                        'name' => $file->name,
                        'size' => $file->size,
                        'file' => $hashname,
                        'path' => 'products/' . $create->id,
                        'full_file' =>  $new_path,
                        'mime_type' => $file->mime_type,
                        'file_type' => 'product',
                        'relation_id' => $create->id
                    ]);
                }
            }

            // copy malls
            foreach ($copyAsModel->productMalls()->get() as $mall) {
                ProductMall::create([
                    'product_id' => $create->id,
                    'mall_id' => $mall->mall_id
                ]);
            }

            // copy other data
            foreach ($copyAsModel->other_data()->get() as $otherData) {
                OtherData::create([
                    'product_id' =>  $create->id,
                    'data_key' => $otherData->data_key,
                    'data_value' => $otherData->data_value,
                ]);
            }

            return response([
                'status' => true,
                'message' => trans('admin.product_copied_success'),
                'id' => $create->id
            ], 200);
        } else {
            return redirect(aurl('/'));
        }
    }

    // search about product
    public function product_search()
    {
        if (request()->ajax()) {
            if (!empty(request('search')) && request()->has('search')) {
                // get the related products according to product_id => already added
                $related_Product = RelatedProduct::where('product_id', request('id'))->get(['related_product']);

                $products = Product::where('title', 'LIKE', '%' . request('search') . '%')
                    ->where('id', '!=', request('id')) // except the current product I edit it
                    ->whereNotIn('id', $related_Product) // means dont give this data
                    ->orderBy('id', 'desc')
                    ->limit(15)->get();

                return response([
                    'status' => true,
                    'result' => count($products) > 0 ? $products : 'No data matched',
                    'count'  => count($products)
                ], 200);
            }
        }
    }

    // upload main photo
    public function update_main_photo($id)
    {
        $product = Product::where('id', $id)->update([
            'photo' => up()->upload([
                'file' => 'file',
                'path' => 'products/' . $id,
                'upload_type' => 'single',
                'delete_file' => '',
            ]),
        ]);
        return response(['status' => true], 200);
    }

    // upload photos
    public function upload_files($id)
    {
        // dd(request());
        if (request()->hasFile('file')) {
            $fid = up()->upload([
                'file' => 'file',
                'path' => 'products/' . $id,
                'upload_type' => 'files',
                'file_type' => 'product',
                'relation_id' => $id,
            ]);
            return response(['status' => true, 'id' => $fid], 200);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view(
            'admin.products.product',
            [
                'title' => trans(
                    'admin.create_or_edit_product',
                    ['title' => $product->title]
                ),
                'product' => $product
            ]
        );

        return view('admin.products.edit', compact('color', 'title'));
    }

    // delete main image  => from dropzone
    public function delete_main_image($id)
    {
        $product = Product::find($id);
        Storage::delete($product->photo);
        $product->photo = null;
        $product->save();
    }

    // delete single file from File table => from dropzone
    public function delete_file()
    {
        if (request()->has('id')) {
            up()->delete(request('id'));
        }
    }

    // delete multiple files from File table => using delete button (entire product deletion)
    public function delete_products_files($id)
    {
        $product = Product::find($id);
        Storage::delete($product->photo); // delete main photo from storage
        up()->delete_files($id);
        $product->delete();
    }

    // delete single product with files
    public function destroy($id)
    {
        $this->delete_products_files($id);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('products'));
    }

    // delete multi product with files
    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $this->delete_products_files($id);
            }
        } else {
            $this->delete_products_files(request('item'));
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('products'));
    }
}
