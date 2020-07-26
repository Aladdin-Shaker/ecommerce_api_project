<?php

namespace App\Http\Controllers\API\Mall;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\DataTables\MallDataTable;
use Illuminate\Http\Request;
use App\Model\Mall;

class MallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $mall->render('admin.malls.index', ['title' =>  trans('admin.malls')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.malls.create', ['title' => trans('admin.create_mall')]);
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
        Mall::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('malls'));
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
        $mall = Mall::find($id);
        $title = trans('admin.edit');
        return view('admin.malls.edit', compact('mall', 'title'));
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

        Mall::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('malls'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mall = Mall::find($id);
        $mall->delete();
        Storage::delete($mall->icon);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('malls'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $mall = Mall::find($id);
                Storage::delete($mall->icon);
                $mall->delete();
            }
        } else {
            $mall = Mall::find(request('item'));
            $mall->delete();
            Storage::delete($mall->icon);
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('malls'));
    }
}
