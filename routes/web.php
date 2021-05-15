<?php

use App\Models\Site;
use App\Valet\Valet;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


Route::get('/', function () {
    return view('welcome');
});

