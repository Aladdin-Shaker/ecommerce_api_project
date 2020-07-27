<?php

namespace App\Http\Controllers\API\Country;

use App\Http\Controllers\API\ApiController;
use App\Model\Country;
use Illuminate\Support\Facades\Storage;

class CountryController extends ApiController
{

    public function index()
    {
        $data = Country::all();
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
        $rules = [
            'country_name_ar' => 'required',
            'country_name_en' => 'required',
            'mob' => 'required',
            'code' => 'required',
            'currency' => 'required',
            'logo' => 'sometimes|nullable|' . v_image(),
        ];

        $data =  $this->validate(request(), $rules);

        if (request()->has('logo')) {
            $data['logo'] = up()->upload([
                'file' => 'logo',
                'path' => 'countries',
                'upload_type' => 'single',
                'delete_file' => '',

            ]);
        }
        $country = Country::create($data);
        return $this->sendOne('country adedd successfully', $country, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::findOrfail($id);
        return $this->sendOne('success', $country, [], true);
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
            'country_name_ar' => 'required',
            'country_name_en' => 'required',
            'mob' => 'required',
            'code' => 'required',
            'currency' => 'required',
            'logo' => 'required|' . v_image(),
        ]);
        if (request()->has('logo')) {
            $data['logo'] = up()->upload([
                'file' => 'logo',
                'path' => 'public/countries',
                'upload_type' => 'single',
                'delete_file' => Country::find($id)->logo,

            ]);
        }
        $country = Country::findOrfail($id);
        $country->update($data);
        return $this->sendOne('country updated successfully', $country, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::findOrfail($id);
        $country->delete();
        Storage::delete($country->logo);
        return $this->sendOne('country deleted successfully', $country, [], true);
    }
}
