<?php

namespace App\Http\Controllers\API\Color;

use Illuminate\Support\Facades\Storage;
use App\DataTables\ColorDataTable;
use App\Http\Controllers\API\ApiController;
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
        $data = Color::all();
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
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'color' => 'required'
        ]);
        $color = Color::create($data);
        return $this->sendOne('color adedd successfully', $color, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $color = Color::findOrfail($id);
        return $this->sendOne('success', $color, [], true);
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
        $color = Color::findOrfail($id);
        $color->update($data);
        return $this->sendOne('color updated successfully', $color, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = Color::findOrfail($id);
        $color->delete();
        return $this->sendOne('color deleted successfully', $color, [], true);
    }
}
