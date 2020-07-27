<?php

namespace App\Http\Controllers\API\Shipping;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Model\Shipping;

class ShippingController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Shipping::all();
        return $this->sendResult('success', $data, [], true);
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
        $shipping = Shipping::create($data);
        return $this->sendOne('shipping adedd successfully', $shipping, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shipping = Shipping::findOrfail($id);
        return $this->sendOne('success', $shipping, [], true);
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
        $shipping = Shipping::findOrfail($id);
        $shipping->update($data);
        return $this->sendOne('shipping updated successfully', $shipping, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = Shipping::findOrfail($id);
        $shipping->delete();
        Storage::delete($shipping->icon);
        return $this->sendOne('shipping deleted successfully', $shipping, [], true);
    }
}
