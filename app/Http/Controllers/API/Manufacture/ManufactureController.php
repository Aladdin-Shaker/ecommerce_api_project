<?php

namespace App\Http\Controllers\API\Manufacture;

use Illuminate\Support\Facades\Storage;
use App\DataTables\ManufactureDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\City;
use App\Model\Manufacture;

class ManufactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $manufacture->render('admin.manufactures.index', ['title' =>  trans('admin.manufactures')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manufactures.create', ['title' => trans('admin.create_manufacture')]);
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
        Manufacture::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('manufactures'));
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
        $manufacture = Manufacture::find($id);
        $title = trans('admin.edit');
        return view('admin.manufactures.edit', compact('manufacture', 'title'));
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

        Manufacture::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('manufactures'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manufacture = Manufacture::find($id);
        $manufacture->delete();
        Storage::delete($manufacture->icon);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('manufactures'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $manufacture = Manufacture::find($id);
                Storage::delete($manufacture->icon);
                $manufacture->delete();
            }
        } else {
            $manufacture = Manufacture::find(request('item'));
            $manufacture->delete();
            Storage::delete($manufacture->icon);
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('manufactures'));
    }
}
