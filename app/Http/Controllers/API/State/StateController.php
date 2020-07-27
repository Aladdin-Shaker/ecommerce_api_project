<?php

namespace App\Http\Controllers\API\State;

use App\DataTables\StateDataTable;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\Controller;
use App\Model\City;
use App\Model\State;
use Illuminate\Http\Request;
use Form;

class StateController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = State::all();
        return $this->sendResult('success', $data, [], true);
    }

    public function store()
    {
        $data =  $this->validate(request(), [
            'state_name_ar' => 'required',
            'state_name_en' => 'required',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);

        $state = State::create($data);
        return $this->sendOne('state adedd successfully', $state, [], true);
    }

    public function show($id)
    {
        $state = State::findOrfail($id);
        return $this->sendOne('success', $state, [], true);
    }

    public function update($id)
    {
        $data =  $this->validate(request(), [
            'state_name_ar' => 'required',
            'state_name_en' => 'required',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);
        $state = State::findOrfail($id);
        $state->update($data);
        return $this->sendOne('state updated successfully', $state, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::findOrfail($id);
        $state->delete();
        return $this->sendOne('state deleted successfully', $state, [], true);
    }
}
