<?php

namespace App\Http\Controllers\API\Department;

use Illuminate\Support\Facades\Storage;
use App\DataTables\CityDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.departments.index', ['title' => trans('admin.departments')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.departments.create', ['title' => trans('admin.create_department')]);
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
        Department::create($data);
        session()->flash('success', trans('admin.record_added'));
        return redirect(aurl('departments'));
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
        $department = Department::find($id);
        $title = trans('admin.edit');
        return view('admin.departments.edit', compact('department', 'title'));
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
        Department::where('id', $id)->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('departments'));
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
        self::delete_parent($id);
        session()->flash('success', trans('admin.record_deleted'));
        return redirect(aurl('departments'));
    }
}
