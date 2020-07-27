<?php

namespace App\Http\Controllers\API\Manufacture;

use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Model\Manufacture;

class ManufactureController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Manufacture::all();
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
                'path' => 'manufactures',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }
        $manufacture = Manufacture::create($data);
        return $this->sendOne('manufacture adedd successfully', $manufacture, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manufacture = Manufacture::findOrfail($id);
        return $this->sendOne('success', $manufacture, [], true);
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
            'name_ar' => 'required',
            'name_en' => 'required',
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
                'path' => 'manufactures',
                'upload_type' => 'single',
                'delete_file' => Manufacture::find($id)->icon,
            ]);
        }
        $manufacture = Manufacture::findOrfail($id);
        $manufacture->update($data);
        return $this->sendOne('manufacture updated successfully', $manufacture, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manufacture = Manufacture::findOrfail($id);
        $manufacture->delete();
        Storage::delete($manufacture->icon);
        return $this->sendOne('manufacture deleted successfully', $manufacture, [], true);
    }
}
