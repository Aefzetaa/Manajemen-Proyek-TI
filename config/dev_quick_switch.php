<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dev Quick Switch (floating account switcher)
    |--------------------------------------------------------------------------
    |
    | Alat bantu untuk ganti akun tanpa login ulang (testing / demo offline).
    | Aktif secara default. Set DEV_QUICK_SWITCH=false di .env untuk mematikan.
    |
    */

    'enabled' => filter_var(env('DEV_QUICK_SWITCH', true), FILTER_VALIDATE_BOOLEAN),

];
