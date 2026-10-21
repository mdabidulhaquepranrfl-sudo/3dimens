<?php

use Illuminate\Support\Facades\Route;

// Load Admin Module Web Routes
if (file_exists(base_path('app/Modules/Admin/Routes/web.php'))) {
    require base_path('app/Modules/Admin/Routes/web.php');
}
