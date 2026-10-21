<?php

use Illuminate\Support\Facades\Route;

// Load Store Module Web Routes
if (file_exists(base_path('app/Modules/Store/Routes/web.php'))) {
    require base_path('app/Modules/Store/Routes/web.php');
}
