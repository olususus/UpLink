<?php

use App\Models\Setting;

if (!function_exists('site_logo_url')) {
    function site_logo_url()
    {
        $logo = Setting::where('key', 'logo')->first();
        return $logo ? asset('storage/' . $logo->value) : null;
    }
}
