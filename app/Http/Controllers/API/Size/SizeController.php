<?php

namespace App\Http\Controllers\API\Size;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\Model\Size;

class SizeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Size::all();
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
            'is_public' => 'required|in:yes,no',
            'department_id' => 'required|numeric'
        ]);
        $size = Size::create($data);
        return $this->sendOne('size adedd successfully', $size, [], true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $size = Size::findOrfail($id);
        return $this->sendOne('success', $size, [], true);
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
        $size = Size::findOrfail($id);
        $size->update($data);
        return $this->sendOne('size updated successfully', $size, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $size = Size::findOrfail($id);
        $size->delete();
        return $this->sendOne('size deleted successfully', $size, [], true);
    }
}
