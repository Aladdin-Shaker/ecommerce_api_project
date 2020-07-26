<?php

namespace App\Http\Controllers\API\User;

use App\User;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $user->render('admin.users.index', ['title' => trans('admin.users')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create', ['title' => trans('admin.create_user')]);
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
            'name' => 'required',
            'email' => 'required|unique:admins',
            'password' => 'required|min:6',
            'level' => 'required|in:user,vendor,company',
        ]);
        $data['password'] = bcrypt(request('password'));
        User::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('users'));
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
        $user = User::find($id);
        $title = trans('admin.edit');
        return view('admin.users.edit', compact('user', 'title'));
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
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . $id,
            'password' => 'sometimes|nullable|min:6',
            'level' => 'required|in:user,vendor,company',
        ]);
        if (request()->has('password')) {
            $data['password'] = bcrypt(request('password'));
        }
        User::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('users'));
    }

    public function multi_delete()
    {
        if (is_array(request('item'))) {
            User::destroy(request('item'));
        } else {
            User::find(request('item'))->delete();
        }
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('users'));
    }
}
