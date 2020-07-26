<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Admin;
use Illuminate\Support\Facades\DB;

class AdminController extends ApiController
{
    // public function __construct()
    // {
    //     // $this->targetModel = new Admin;
    // }

    public function index()
    {
        $data = Admin::all();
        return $this->sendResult('success', $data, [], true);
    }

    public function update(Request $request, $id)
    {
        // dd(request()->all());

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . $id,
            'password' => 'required|min:6',
        ];

        $data = $this->validate($request, $rules);
        if (request()->has('password')) {
            $data['password'] = bcrypt(request('password'));
        }

        $admin = DB::table('admins')->where('id', $id);
        return ($admin);
        $admin->update($data);
        return $this->sendResult('Admin data updated successfuly', $admin, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::findOrfail($id);
        $admin->delete();
        return $this->showOne($admin);
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            $admin = Admin::destroy(request('item'));
        } else {
            $admin = Admin::findOrfail(request('item'))->delete();
        }
        return $this->showOne($admin);
    }
}
