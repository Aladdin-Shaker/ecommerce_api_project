<?php

use GuzzleHttp\Psr7\Request;

if (!function_exists('aurl')) {
    function aurl($url = null)
    {
        return url('admin/' . $url);
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return auth()->guard('admin');
    }
}

if (!function_exists('lang')) {
    function lang()
    {
        if (session()->has('lang')) {
            return session('lang');
        } else {
            return session()->put(setting()->main_lang);
        }
    }
}

if (!function_exists('direction')) {
    function direction()
    {
        if (session()->has('lang')) {
            if (session('lang') == 'ar') {
                return 'rtl';
            } else {
                return 'ltr';
            }
        } else {
            return 'ltr';
        }
    }
}

if (!function_exists('datatable_lang')) {
    function datatable_lang()
    {
        return [
            "sProcessing" => trans('admin.sProcessing'),
            "sLengthMenu" => trans('admin.sLengthMenu'),
            "sZeroRecords" => trans('admin.sZeroRecords'),
            "sEmptyTable" => trans('admin.sEmptyTable'),
            "sInfo" => trans('admin.sInfo'),
            "sInfoEmpty" => trans('admin.sInfoEmpty'),
            "sInfoFiltered" =>  trans('admin.sInfoFiltered'),
            "sInfoPostFix" => trans('admin.sInfoPostFix'),
            "sSearch" => trans('admin.sSearch'),
            "sUrl" => trans('admin.sUrl'),
            "sInfoThousands" => trans('admin.sInfoThousands'),
            "sLoadingRecords" => trans('admin.sLoadingRecords'),
            "oPaginate" => [
                "sFirst" => trans('admin.sFirst'),
                "sLast" => trans('admin.sLast'),
                "sNext" => trans('admin.sNext'),
                "sPrevious" => trans('admin.sPrevious')
            ],
            "oAria" => [
                "sSortAscending" => trans('admin.sSortAscending'),
                "sSortDescending" => trans('admin.sSortDescending')
            ]
        ];
    }
}

if (!function_exists('active_menu')) {
    function active_menu($link)
    {
        if (preg_match('/' . $link . '/i', request()->segment(2))) {
            return ['menu-open', 'display:block'];  // [0, 1]
        } else {
            return ['', ''];
        }
    }
}

if (!function_exists('setting')) {
    function setting()
    {
        return \App\Model\Setting::orderBy('id', 'desc')->first(); // call the last value of setings
    }
}

if (!function_exists('up')) {
    function up()
    {
        return new \App\Http\Controllers\Upload; // call the last value of setings
    }
}

// get the department with all his parent
if (!function_exists('get_dep_parent')) {
    function get_dep_parent($dep_id)
    {
        $department = \App\Model\Department::find($dep_id);
        if ($department->parent !== null && $department->parent > 0) { // check if there a parent
            return get_dep_parent($department->parent) . ',' . $dep_id; // call self function to loop all parent and get
        } else {
            return $dep_id; // there is no parent
        }
    }
}

if (!function_exists('load_dep')) {
    function load_dep($select = null, $dep_hide = null)
    {
        $departments = \App\Model\Department::selectRaw('dep_name_' . session('lang') . ' as text')
            ->selectRaw('parent as parent')
            ->selectRaw('id as id')
            ->get(['text', 'id', 'parent']);
        $dep_array = [];
        foreach ($departments as $department) {
            $list_array = [];
            $list_array['icon'] = '';
            $list_array['li_attr'] = '';
            $list_array['a_attr'] = '';
            $list_array['children'] = [];
            if ($select !== null and $select == $department->id) {
                $list_array['state'] = [
                    'opened' => true,
                    'selected' => true,
                    'disabled' => false
                ];
            }
            if ($dep_hide !== null and $dep_hide == $department->id) {
                $list_array['state'] = [
                    'opened' => false,
                    'selected' => false,
                    'disabled' => true,
                    'hidden' => true
                ];
            }
            $list_array['id'] = $department->id;
            $list_array['parent'] = $department->parent !== null ? $department->parent : '#';
            $list_array['text'] = $department->text;

            array_push($dep_array, $list_array);
        }
        return json_encode($dep_array, JSON_UNESCAPED_UNICODE);
    }
}

// scan mall id exist
if (!function_exists('checkMall')) {
    function checkMall($mid, $pid)
    {
        return  \App\Model\ProductMall::where('mall_id', $mid)
            ->where('product_id', $pid)
            ->count() > 0 ? true : false;
    }
}

////////////////// Validation helper functions ///////////////////

if (!function_exists('v_image')) {
    function v_image($ex = null)
    {
        if ($ex === null) {
            return 'image|mimes:png,jpg,jpeg,gif';
        } else {
            return 'image|mimes:' . $ex;
        }
    }
}
