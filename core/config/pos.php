<?php

return [
    /*
    |--------------------------------------------------------------------------
    | POS Application Version
    |--------------------------------------------------------------------------
    |
    | This value is the latest version of the POS desktop application.
    | The desktop app checks this value to determine if an update is needed.
    |
    */
    'latest_version' => env('POS_LATEST_VERSION', '1.0.0'),

    /*
    |--------------------------------------------------------------------------
    | Forced Update
    |--------------------------------------------------------------------------
    |
    | If true, the desktop app will block usage until updated.
    | Useful for breaking API changes.
    |
    */
    'force_update' => env('POS_FORCE_UPDATE', false),

    /*
    |--------------------------------------------------------------------------
    | Download URL
    |--------------------------------------------------------------------------
    |
    | The direct link where the user can download the latest installer.
    |
    */
    'download_url' => env('POS_DOWNLOAD_URL', 'https://rezervist.com/download/pos'),

    /*
    |--------------------------------------------------------------------------
    | Update Message
    |--------------------------------------------------------------------------
    |
    | Message to display to the user when an update is available.
    |
    */
    'update_message' => 'Yeni güncellemeler mevcut! Performans iyileştirmeleri ve hata düzeltmeleri yapıldı.',
];
