<?php

namespace App\Http\Controllers\API\Mall;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Model\Mall;

class MallController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Mall::all();
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
            'name_ar' => 'required',
            'name_en' => 'required',
            'country_id' => 'required|numeric',
            'email' => 'sometimes|nullable|email',
            'mobile' => 'sometimes|nullable|numeric',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'website' => 'sometimes|nullable|url',
            'contact_name' => 'sometimes|nullable',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|' . v_image()
        ]);
        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'malls',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }
        $mall = Mall::create($data);
        return $this->sendOne('mall adedd successfully', $mall, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mall = Mall::findOrfail($id);
        return $this->sendOne('success', $mall, [], true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $data =  $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'country_id' => 'required|numeric',
            'email' => 'sometimes|nullable|email',
            'mobile' => 'sometimes|nullable|numeric',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'website' => 'sometimes|nullable|url',
            'contact_name' => 'sometimes|nullable',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|' . v_image()
        ]);
        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'malls',
                'upload_type' => 'single',
                'delete_file' => Mall::find($id)->icon,
            ]);
        }
        $mall = Mall::findOrfail($id);
        $mall->update($data);
        return $this->sendOne('mall updated successfully', $mall, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mall = Mall::findOrfail($id);
        $mall->delete();
        Storage::delete($mall->icon);
        return $this->sendOne('mall deleted successfully', $mall, [], true);
    }
}
