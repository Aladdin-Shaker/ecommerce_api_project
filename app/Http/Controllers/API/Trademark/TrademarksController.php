<?php

namespace App\Http\Controllers\API\Trademark;

use Illuminate\Support\Facades\Storage;
use App\DataTables\TrademarksDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\City;
use App\Model\TradeMarks;

class TrademarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $trade->render('admin.trademarks.index', ['title' =>  trans('admin.trademarks')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.trademarks.create', ['title' => trans('admin.create_trademark')]);
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
            'logo' => 'required|' . v_image()
        ]);
        if (request()->has('logo')) {
            $data['logo'] = up()->upload([
                'file' => 'logo',
                'path' => 'trademarks',
                'upload_type' => 'single',
                'delete_file' => '',
            ]);
        }
        TradeMarks::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('trademarks'));
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
        $trade = TradeMarks::find($id);
        $title = trans('admin.edit');
        return view('admin.trademarks.edit', compact('trade', 'title'));
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
            'logo' => 'required|' . v_image()
        ]);
        if (request()->has('logo')) {
            $data['logo'] = up()->upload([
                'file' => 'logo',
                'path' => 'trademarks',
                'upload_type' => 'single',
                'delete_file' => Trademarks::find($id)->logo,
            ]);
        }

        TradeMarks::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('trademarks'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trade = Trademarks::find($id);
        $trade->delete();
        Storage::delete($trade->logo);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('trademarks'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            foreach (request('item') as $id) {
                $trade = Trademarks::find($id);
                Storage::delete($trade->logo);
                $trade->delete();
            }
        } else {
            $trade = Trademarks::find(request('item'));
            $trade->delete();
            Storage::delete($trade->logo);
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('trademarks'));
    }
}
