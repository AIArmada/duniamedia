<?php
return [
 'name'=>env('APP_NAME','Dunia Media'), 'env'=>env('APP_ENV','production'), 'debug'=>(bool)env('APP_DEBUG',false),
 'url'=>env('APP_URL','http://localhost'), 'timezone'=>'Asia/Kuala_Lumpur', 'locale'=>env('APP_LOCALE','en'),
 'fallback_locale'=>env('APP_FALLBACK_LOCALE','en'), 'faker_locale'=>'en_MY',
 'key'=>env('APP_KEY'), 'cipher'=>'AES-256-CBC', 'maintenance'=>['driver'=>'file'],
];
