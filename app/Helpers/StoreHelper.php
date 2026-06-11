<?php

use App\Models\StoreSetting;

if (!function_exists('store_setting')) {

    function store_setting($column, $default = '')
    {

        $store = StoreSetting::first();

        return $store && !empty($store->$column) ? $store->$column : $default;
    }
}
