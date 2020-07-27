<?php

namespace App\Http\Controllers\API\Settings;

use App\Http\Controllers\API\ApiController;
use App\Model\Setting;
use Illuminate\Support\Facades\DB;
use Up;

class Settings extends ApiController
{
    public function settings()
    {
        $data = Setting::get();
        return $this->sendOne('success', $data, [], true);
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

        DB::table('settings')->update($data);
        $result = Setting::get();

        return $this->sendOne('success', $result, [], true);
    }
}
