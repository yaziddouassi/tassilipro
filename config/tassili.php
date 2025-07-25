<?php

return  [
    'storage_disk' => env('TASSILI_STORAGE_DISK', 'public'),
    'storage_url' =>  env('TASSILI_STORAGE_URL', 'http://127.0.0.1:8000/storage/'),
    'company' =>'My Company',
    'panelList' => ['admin'],
    'middlewareList' => ['auth'],
    'modelList' => ['User'],
];