<?php

return array(


    'pdf' => array(
        'enabled' => true,
        // 'binary' => "\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\"",
        // 'binary' =>  "\"".base_path(env('SNAPPY_PATH', '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'))."\"",
        'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => array(
            'footer-center' => 'Page [page] of [toPage]',
            'footer-font-size' => 8,
        ),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => base_path('vendor\wemersonjanuario\wkhtmltoimage-windows\bin\64bit\wkhtmltoimage'),
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
