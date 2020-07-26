<?php

namespace App\Http\Controllers\API\Color;

use Illuminate\Support\Facades\Storage;
use App\DataTables\ColorDataTable;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Color;

class ColorController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  return $color->render('admin.colors.index', ['title' =>  trans('admin.colors')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.colors.create', ['title' => trans('admin.create_color')]);
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
            'color' => 'required'
        ]);
        Color::create($data);
        return $this->sendResult('success', $data, [], true);
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
        $color = Color::find($id);
        $title = trans('admin.edit');
        return view('admin.colors.edit', compact('color', 'title'));
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
            'color' => 'required'
        ]);

        Color::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('colors'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = Color::find($id);
        $color->delete();
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('colors'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $color = Color::find($id);
                $color->delete();
            }
        } else {
            $color = Color::find(request('item'));
            $color->delete();
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('colors'));
    }
}
