<?php

namespace App\Http\Controllers\API\Department;

use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Model\Department;

class DepartmentController extends ApiController
{

    public function index()
    {
        $data = Department::all();
        return $this->sendResult('success', $data, [], true);
    }

    public function store()
    {
        $data =  $this->validate(request(), [
            'dep_name_ar' => 'required',
            'dep_name_en' => 'required',
            'icon' => 'sometimes|nullable|' . v_image(),
            'description' => 'sometimes|nullable',
            'keyword' => 'sometimes|nullable',
            'parent' => 'sometimes|nullable|numeric',
        ]);

        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'departments',
                'upload_type' => 'single',
                'delete_file' => ''
            ]);
        }
        $department = Department::create($data);
        return $this->sendOne('department adedd successfully', $department, [], true);
    }

    public function show($id)
    {
        $department = Department::findOrfail($id);
        return $this->sendOne('success', $department, [], true);
    }

    public function update($id)
    {
        $data =  $this->validate(request(), [
            'dep_name_ar' => 'required',
            'dep_name_en' => 'required',
            'icon' => 'sometimes|nullable|' . v_image(),
            'description' => 'sometimes|nullable',
            'keyword' => 'sometimes|nullable',
            'parent' => 'sometimes|nullable|numeric',
        ]);
        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'departments',
                'upload_type' => 'single',
                'delete_file' => Department::find($id)->icon,

            ]);
        }
        $department = Department::findOrfail($id);
        $department->update($data);
        return $this->sendOne('department updated successfully', $department, [], true);
    }


    // delete all sub departments with icons for specific department
    public static function delete_parent($id)
    {
        $department_parent = Department::where('parent', $id)->get();
        foreach ($department_parent as $sub) { // loop to get all the down departments
            self::delete_parent($sub->id); // to get all down of down departments
            if (!empty($sub->icon)) {
                Storage::has($sub->icon) ? Storage::delete($sub->icon) : '';
            }
            $dep = Department::find($sub->id);
            if (!empty($dep)) {
                $dep->delete(); // delete all down
            }
        }
        // delete the current department
        $parent_dep = Department::find($id);
        // return (dd($parent_dep));
        if (!empty($parent_dep->icon)) {
            Storage::has($parent_dep->icon) ? Storage::delete($parent_dep->icon) : '';
        }
        $parent_dep->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $department = self::delete_parent($id);
        // return $this->sendOne('department deleted successfully', $department, [], true);
    }
}
