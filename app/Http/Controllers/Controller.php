<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

// TODO: Задачи
//ssh -L 3306:127.0.0.1:3306 root@5.23.48.206
//bgMZdJE8

//ln -s /var/www/lavash/storage/app/public/ /var/www/lavash/public/storage
//ln -s /var/www/lavash/storage/app/products/ /var/www/lavash/storage/app/public/products
