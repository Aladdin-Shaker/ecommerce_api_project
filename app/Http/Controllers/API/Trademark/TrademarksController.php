<?php

namespace App\Http\Controllers\API\Trademark;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\API\ApiController;
use App\Model\TradeMarks;

class TrademarksController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TradeMarks::all();
        return $this->sendResult('success', $data, [], true);
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
        $trademark = TradeMarks::create($data);
        return $this->sendOne('trademark adedd successfully', $trademark, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trademark = TradeMarks::findOrfail($id);
        return $this->sendOne('success', $trademark, [], true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
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
                'delete_file' => Trademarks::findOrfail($id)->logo,
            ]);
        }
        $trademark = TradeMarks::findOrfail($id);
        $trademark->update($data);
        return $this->sendOne('trademark updated successfully', $trademark, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trademark = Trademarks::findOrfail($id);
        $trademark->delete();
        Storage::delete($trademark->logo);
        return $this->sendOne('trademark deleted successfully', $trademark, [], true);
    }
}
