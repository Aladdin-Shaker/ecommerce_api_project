<?php

namespace App\Http\Controllers\API\State;

use App\DataTables\StateDataTable;
use App\Http\Controllers\Controller;
use App\Model\City;
use App\Model\State;
use Illuminate\Http\Request;
use Form;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $state->render('admin.states.index', ['title' =>  trans('admin.states')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->ajax()) {
            if (request()->has('country_id')) {
                $select = request()->has('select') ? request('select') : '';
                return Form::select(
                    'city_id',
                    City::where('country_id', request('country_id'))
                        ->pluck('city_name_' . session('lang'), 'id'),
                    $select,
                    ['class' => 'form-control', 'placeholder' => '..........']
                );
            }
        }
        return view('admin.states.create', ['title' => trans('admin.create_state')]);
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
            'state_name_ar' => 'required',
            'state_name_en' => 'required',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);

        State::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('states'));
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
        $state = State::find($id);
        $title = trans('admin.edit');
        return view('admin.states.edit', compact('state', 'title'));
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
            'state_name_ar' => 'required',
            'state_name_en' => 'required',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);
        State::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('states'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::find($id);
        $state->delete();
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('states'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $state = State::find($id);
                $state->delete();
            }
        } else {
            $state = State::find(request('item'));
            $state->delete();
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('states'));
    }
}
