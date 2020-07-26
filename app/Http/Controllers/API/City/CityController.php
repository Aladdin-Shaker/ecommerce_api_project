<?php

namespace App\Http\Controllers\API\City;

use Illuminate\Support\Facades\Storage;
use App\DataTables\CityDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\City;

class cityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  return $city->render('admin.cities.index', ['title' =>  trans('admin.cities')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cities.create', ['title' => trans('admin.create_city')]);
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
            'city_name_ar' => 'required',
            'city_name_en' => 'required',
            'country_id' => 'required|numeric',
        ]);

        City::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('cities'));
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
        $city = City::find($id);
        $title = trans('admin.edit');
        return view('admin.cities.edit', compact('city', 'title'));
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
            'city_name_ar' => 'required',
            'city_name_en' => 'required',
            'country_id' => 'required|numeric',
        ]);
        City::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('cities'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::find($id);
        $city->delete();
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('cities'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $city = City::find($id);
                $city->delete();
            }
        } else {
            $city = City::find(request('item'));
            $city->delete();
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('cities'));
    }
}
