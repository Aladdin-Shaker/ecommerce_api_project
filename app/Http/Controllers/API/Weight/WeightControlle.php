<?php

namespace App\Http\Controllers\API\Weight;

use App\DataTables\WeightDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Weight;

class WeightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  return $weight->render('admin.weights.index', ['title' =>  trans('admin.weights')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.weights.create', ['title' => trans('admin.create_weight')]);
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
        Weight::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('weights'));
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
        $weight = Weight::find($id);
        $title = trans('admin.edit');
        return view('admin.weights.edit', compact('weight', 'title'));
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
            'name_en' => 'required|string'
        ]);

        Weight::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('weights'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $weight = Weight::find($id);
        $weight->delete();
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('weights'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $weight = Weight::find($id);
                $weight->delete();
            }
        } else {
            $weight = Weight::find(request('item'));
            $weight->delete();
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('weights'));
    }
}
