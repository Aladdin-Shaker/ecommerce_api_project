<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\Controller;
use App\Model\Setting;
use Up;

class Settings extends Controller
{
    public function settings()
    {
        return view('admin.settings', ['title' => trans('admin.settings')]);
    }

    public function settings_save()
    {
        $data = $this->validate(request(), [
            'logo' => v_image(),
            'icon' => v_image(),
            'sitename_ar' => '',
            'sitename_en' => '',
            'email' => '',
            'main_lang' => '',
            'description' => '',
            'keywords' => '',
            'status' => '',
            'message_maintenance' => ''
        ]);
        if (request()->has('logo')) {
            $data['logo'] = up()->upload([
                'file' => 'logo',
                'path' => 'settings',
                'upload_type' => 'single',
                'delete_file' => setting()->logo,

            ]);
        }
        if (request()->has('icon')) {
            $data['icon'] = up()->upload([
                'file' => 'icon',
                'path' => 'settings',
                'upload_type' => 'single',
                'delete_file' => setting()->icon,

            ]);
        }

        Setting::orderBy('id', 'desc')->update($data);
        session()->flash('success', trans('admin.record_updated'));
        return redirect(aurl('settings'));
    }
}
