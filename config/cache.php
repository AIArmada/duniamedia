<?php
return ['default'=>env('CACHE_STORE','file'),'stores'=>['file'=>['driver'=>'file','path'=>storage_path('framework/cache/data')],'array'=>['driver'=>'array','serialize'=>false],'database'=>['driver'=>'database','connection'=>null,'table'=>'cache']],'prefix'=>'duniamedia_cache_'];
