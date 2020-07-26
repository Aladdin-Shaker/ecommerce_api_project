<?php

namespace App\Http\Controllers\API\Size;

use App\DataTables\SizeDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Size;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $size->render('admin.sizes.index', ['title' =>  trans('admin.sizes')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sizes.create', ['title' => trans('admin.create_size')]);
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
            'name_en' => 'required|string',
            'is_public' => 'required|in:yes,no',
            'department_id' => 'required|numeric'
        ]);
        Size::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('sizes'));
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
        $size = Size::find($id);
        $title = trans('admin.edit');
        return view('admin.sizes.edit', compact('size', 'title'));
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
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'is_public' => 'required|in:yes,no',
            'department_id' => 'required|numeric'
        ]);

        Size::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('sizes'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $size = Size::find($id);
        $size->delete();
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('sizes'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $size = Size::find($id);
                $size->delete();
            }
        } else {
            $size = Size::find(request('item'));
            $size->delete();
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('sizes'));
    }
}
