<?php

namespace App\Http\Controllers\API\Country;

use App\Http\Controllers\Controller;
use App\Model\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
{

    public function index()
    {
        //  return $country->render('admin.countries.index', ['title' =>  trans('admin.countries')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create', ['title' => trans('admin.create_country')]);
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
            'country_name_ar' => 'required',
            'country_name_en' => 'required',
            'mob' => 'required',
            'code' => 'required',
            'currency' => 'required',
            'logo' => 'sometimes|nullable|' . v_image(),
        ]);
        if (request()->has('logo')) {
            $data['logo'] = up()->upload([
                'file' => 'logo',
                'path' => 'countries',
                'upload_type' => 'single',
                'delete_file' => '',

            ]);
        }
        Country::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('countries'));
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
        $country = Country::find($id);
        $title = trans('admin.edit');
        return view('admin.countries.edit', compact('country', 'title'));
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
        Country::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('countries'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::find($id);
        $country->delete();
        Storage::delete($country->logo);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('countries'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $country = Country::find($id);
                Storage::delete($country->logo);
                $country->delete();
            }
        } else {
            $country = Country::find(request('item'));
            $country->delete();
            Storage::delete($country->logo);
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('countries'));
    }
}
