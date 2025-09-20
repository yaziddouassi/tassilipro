<?php

return  [
    'storage_disk' => env('TASSILI_STORAGE_DISK', 'public'),
    'storage_url' =>  env('TASSILI_STORAGE_URL', 'http://127.0.0.1:8000/storage/'),
    'storage_folder' => env('TASSILI_STORAGE_DEFAULT_FOLDER', 'files'),
    'company' =>'My Company',
    'panelList' => ['admin'],
    'modelList' => ['User'],
];