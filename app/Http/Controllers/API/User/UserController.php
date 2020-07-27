<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use App\User;

class UserController extends ApiController
{

    public function index()
    {
        $data = User::all();
        return $this->sendResult('success', $data, [], true);
    }

    public function update($id)
    {
        $data =  $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|min:6',
            'level' => 'required|in:user,vendor,company',
        ]);

        if (request()->has('password')) {
            $data['password'] = bcrypt(request('password'));
        }
        $user = User::findOrfail($id);
        $user->update($data);
        return $this->sendOne('User data updated successfuly', $user, [], true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrfail($id);
        $user->delete();
        return $this->sendOne('User deleted successfuly', $user, [], true);
    }
}
