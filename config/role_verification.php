<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Role Verification Codes
    |--------------------------------------------------------------------------
    | Kode rahasia yang harus dimasukkan saat mendaftar sebagai role internal
    | (owner, mechanic, cashier). Pelanggan biasa tidak memerlukan kode.
    |
    */
    'codes' => [
        'owner'    => env('ROLE_CODE_OWNER',    'OWNER2026'),
        'mechanic' => env('ROLE_CODE_MECHANIC', 'MECH2026'),
        'cashier'  => env('ROLE_CODE_CASHIER',  'CASH2026'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Account Limits
    |--------------------------------------------------------------------------
    | Batas maksimal akun per role. null = tidak ada batas.
    |
    */
    'limits' => [
        'owner'    => 1,
        'cashier'  => 2,
        'mechanic' => null,
        'customer' => null,
    ],

];
