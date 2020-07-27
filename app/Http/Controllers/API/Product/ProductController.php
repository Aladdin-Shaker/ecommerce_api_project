<?php

namespace App\Http\Controllers\API\Product;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\ApiController;
use App\Model\Product;
use App\Model\ProductMall;

class ProductController extends ApiController
{

    public function index()
    {
        $data = Product::all();
        return $this->sendResult('success', $data, [], true);
    }

    public function update($id)
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
            'start_at' => 'required|date|after:tomorrow',
            'end_at' => 'required|date|after:start_at',
            'start_offer_at' => 'sometimes|nullable|date|after:tomorrow',
            'end_offer_at' => 'sometimes|nullable|date|after:start_offer_at',
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
        $product = Product::findOrfail($id);
        $product->update($data);
        return $this->sendOne('product updated successfully', $product, [], true);
    }

    // search about product
    public function product_search($pid)
    {

        $query = Product::query();

        if (!empty(request('title')) && request()->has('title')) {
            $query = $query->where('title', 'like', '%' . request()->title . '%')
                ->where('id', '!=', $pid); // except the current product I edit it;
        }
        if (!empty(request('content')) && request()->has('content')) {
            $query = $query->where('content', 'like', '%' . request()->content . '%')
                ->where('id', '!=', $pid); // except the current product I edit it;;
        }
        // dd($query);
        return $this->sendResult('success', [
            'result' => count($query->get()) > 0 ? $query->get() : 'No data matched',
        ], [], true);
    }

    // upload main photo
    public function update_main_photo($id)
    {
        $product = Product::find($id);
        if (request()->file('photo') && $product !== null) {
            $product = Product::findOrfail($id);
            $photo = [
                'photo' => up()->upload([
                    'file' => 'photo',
                    'path' => 'products/' . $id,
                    'upload_type' => 'single',
                    'delete_file' => Product::findOrfail($id)->photo,
                ])
            ];
            $product->update($photo);
            return $this->sendOne('main photo updated successfully', $product, [], true);
        } else {
            return $this->sendExceptionErr(['error' => 'failed to upload image'], 422);
        }
    }

    // upload photos
    public function upload_files($id)
    {
        $product = Product::find($id);
        // dd($product);
        if (request()->hasFile('file') && $product !== null) {
            $data = up()->upload([
                'file' => 'file',
                'path' => 'products/' . $id,
                'upload_type' => 'files',
                'file_type' => 'product',
                'relation_id' => $id,
            ]);
            return $this->sendOne('files uploaded successfully', ['data' => $data], true);
        } else {
            return $this->sendExceptionErr(['error' => 'failed to upload file'], 422);
        }
    }

    // delete multiple files from File table => using delete button (entire product deletion)
    public function delete_products_files($id)
    {
        $product = Product::findOrfail($id);
        if ($product !== null) {
            // dd($product);

            Storage::delete($product->photo); // delete main photo from storage
            up()->delete_files($id); // delete files
            $product->delete(); // delete product data
        } else {
            return $this->sendExceptionErr(['error' => 'failed to delete file'], 422);
        }
    }

    // delete single product with files
    public function destroy($id)
    {
        $this->delete_products_files($id);
        return $this->sendOne('product deleted successfully', '', [], true);
    }
}
