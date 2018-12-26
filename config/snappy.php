<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary' => base_path('vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf'),
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
