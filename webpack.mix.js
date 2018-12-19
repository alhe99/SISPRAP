let mix = require('laravel-mix');

////////////////ARCHIVOS PARA TEMPLATE DE ADMINISTRADOR//////////
mix.styles([
        "resources/assets/AdminTemplate/css/bootstrap.min.css",
        'resources/assets/AdminTemplate/css/chartist.min.css',
        'resources/assets/AdminTemplate/css/chartist-init.css',
        'resources/assets/AdminTemplate/css/chartist-plugin-tooltip.css',
        'resources/assets/AdminTemplate/css/roboto.css',
        'resources/assets/AdminTemplate/css/icon.css',
        'resources/assets/AdminTemplate/css/normalize.min.css',
        'resources/assets/AdminTemplate/css/gallery.css',
    ], 'public/css/admintemplate.css')
    .scripts([

        "resources/assets/AdminTemplate/js/jquery.min.js",
        "resources/assets/AdminTemplate/js/popper.min.js",
        "resources/assets/AdminTemplate/js/jquery.slimscroll.js",
        "resources/assets/AdminTemplate/js/waves.js",
        "resources/assets/AdminTemplate/js/sidebarmenu.js",
        "resources/assets/AdminTemplate/js/sticky-kit.min.js",
        "resources/assets/AdminTemplate/js/jquery.sparkline.min.js",
        "resources/assets/AdminTemplate/js/custom.min.js",
        "resources/assets/AdminTemplate/js/d3.min.js",
        "resources/assets/AdminTemplate/js/c3.min.js",
        "resources/assets/AdminTemplate/js/jQuery.style.switcher.js",
        "resources/assets/PublicTemplate/js/sweetalert2.all.min.js",
        "resources/assets/AdminTemplate/js/vue-mdc-adapter.umd.min.js",


    ], 'public/js/admintemplate.js')

.js(['resources/assets/js/app.js'], 'public/js/app.js');

////////////////ARCHIVOS PARA PUBLIC TEMPLATE //////////
mix.styles([
    "resources/assets/PublicTemplate/css/toastr.min.css",


], 'public/css/publicTemplate.css')

.scripts([
    "resources/assets/PublicTemplate/js/vue.min.js",
    "resources/assets/PublicTemplate/js/axios.min.js",
    "resources/assets/PublicTemplate/js/sweetalert2.all.min.js",

], 'public/js/publicTemplate.js');

////////////////ARCHIVOS PARA LOGIN TEMPLATE//////////

mix.styles([
    'resources/assets/LoginTemplate/css/bootstrap.min.css',
    'resources/assets/LoginTemplate/css/animate.css',
    'resources/assets/LoginTemplate/css/hamburgers.min.css',
    'resources/assets/LoginTemplate/css/animsition.min.css',
    'resources/assets/LoginTemplate/css/util.css',
    'resources/assets/LoginTemplate/css/main.css',

], 'public/css/loginTemplate.css')

.scripts([

    "resources/assets/LoginTemplate/js/jquery-3.2.1.min.js",
    "resources/assets/LoginTemplate/js/popper.min.js",
    "resources/assets/LoginTemplate/js/main.js",
    "resources/assets/LoginTemplate/js/bootstrap.min.js",
    "resources/assets/LoginTemplate/js/animsition.min.js",

], 'public/js/loginTemplate.js');