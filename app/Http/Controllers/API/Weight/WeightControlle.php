<?php

namespace App\Http\Controllers\API\Weight;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Model\Weight;

class WeightController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Weight::all();
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
            'name_en' => 'required|string'
        ]);
        $weight = Weight::create($data);
        return $this->sendOne('weight adedd successfully', $weight, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $weight = Weight::findOrfail($id);
        return $this->sendOne('success', $weight, [], true);
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
            'name_ar' => 'required|string',
            'name_en' => 'required|string'
        ]);
        $weight = Weight::findOrfail($id);
        $weight->update($data);
        return $this->sendOne('weight updated successfully', $weight, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $weight = Weight::findOrfail($id);
        $weight->delete();
        return $this->sendOne('weight deleted successfully', $weight, [], true);
    }
}
