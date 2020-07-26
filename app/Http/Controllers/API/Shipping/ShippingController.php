<?php

namespace App\Http\Controllers\API\Shipping;

use Illuminate\Support\Facades\Storage;
use App\DataTables\ShippingDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Shipping;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $shipping->render('admin.shippings.index', ['title' =>  trans('admin.shippings')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shippings.create', ['title' => trans('admin.create_shipping')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data =  $this->validate(request(), [
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'user_id' => 'required|numeric',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|' . v_image()
        ]);
        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'shippings',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }
        Shipping::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('shipping'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shipping = Shipping::find($id);
        $title = trans('admin.edit');
        return view('admin.shippings.edit', compact('shipping', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data =  $this->validate(request(), [
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'user_id' => 'required|numeric',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|' . v_image()
        ]);
        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'shippings',
                'upload_type' => 'single',
                'delete_file' => Shipping::find($id)->icon,
            ]);
        }

        Shipping::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('shipping'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = Shipping::find($id);
        $shipping->delete();
        Storage::delete($shipping->icon);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('shipping'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $shipping = Shipping::find($id);
                Storage::delete($shipping->icon);
                $shipping->delete();
            }
        } else {
            $shipping = Shipping::find(request('item'));
            $shipping->delete();
            Storage::delete($shipping->icon);
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('shipping'));
    }
}
