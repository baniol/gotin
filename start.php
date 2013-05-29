<?php

Autoloader::map(array(
    'GotinAuth' => Bundle::path('gotin').'libraries/gotinauth.php',
    'GotinHelper' => Bundle::path('gotin').'libraries/gotinhelper.php',
    'Gotin_Controller' => Bundle::path('gotin').'controllers/gotin.php',

    'User' => Bundle::path('gotin').'models/user.php',
    'Role' => Bundle::path('gotin').'models/role.php',
    'Model' => Bundle::path('gotin').'models/model.php',

    // helper libs
    'Flash' => Bundle::path('gotin').'libraries/flash.php',
    'Mailer' => Bundle::path('gotin').'libraries/mailer.php',
));

Auth::extend('gotinauth', function() {
    return new GotinAuth();
});