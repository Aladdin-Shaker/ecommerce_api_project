<?php

namespace App\Http\Controllers\API\City;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Model\City;

class cityController extends ApiController
{
    public function index()
    {
        $data = City::all();
        return $this->sendResult('success', $data, [], true);
    }

    public function store()
    {
        $data =  $this->validate(request(), [
            'city_name_ar' => 'required',
            'city_name_en' => 'required',
            'country_id' => 'required|numeric',
        ]);

        $city = City::create($data);
        return $this->sendOne('city adedd successfully', $city, [], true);
    }

    public function show($id)
    {
        $city = City::findOrfail($id);
        return $this->sendOne('success', $city, [], true);
    }

    public function update($id)
    {
        $data =  $this->validate(request(), [
            'city_name_ar' => 'required',
            'city_name_en' => 'required',
            'country_id' => 'required|numeric',
        ]);
        $city = City::findOrfail($id);
        $city->update($data);
        return $this->sendOne('city updated successfully', $city, [], true);;
    }

    public function destroy($id)
    {
        $city = City::findOrfail($id);
        $city->delete();
        return $this->sendOne('city deleted successfully', $city, [], true);
    }
}
