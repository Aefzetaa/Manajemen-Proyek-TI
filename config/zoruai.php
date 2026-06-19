<?php

return [

    'greeting' => [
        'default' => 'Halo, :name. Saya ZoruAi, asisten Milky Garage. Ada yang bisa saya bantu hari ini?',
        'owner' => 'Selamat datang, :name. Mode Eksekutif ZoruAi aktif untuk analisis tren, master data, dan operasional Milky Garage.',
    ],

    'owner_executive_hint' => "Mode Eksekutif telah aktif. Anda dapat meminta ringkasan bisnis, mengubah harga pada master data, menerapkan diskon, atau menambah stok sparepart.\n\n"
        . "Contoh perintah: analisa pendapatan hari ini; ubah harga [nama servis] menjadi [nominal]; berikan diskon [persen]% untuk [nama servis].\n\n"
        . 'Semua harga yang disebutkan mengacu pada database Milky Garage, bukan angka contoh umum.',

    'training_path' => database_path('data/zoruai-training'),

    'training_match_threshold' => 0.5,

    'bahasa_formal_threshold' => 0.3,

];