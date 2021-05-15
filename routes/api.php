<?php

use App\Valet\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('sites', function () {
    $sites = (new  Valet())->allSites();
    return response()->json($sites->toArray());
});

Route::get('logs', function () {
    $logs = (new  Valet())->logs();
    return response()->json($logs);
});

Route::get('logs/show', function (Request $request) {
    $log = (new  Valet())->loadLogFile($request->path, $request->take, $request->skip);
    return response()->json($log);
});

Route::get('on-latest-version', function (Request $request) {
    $onLatestVerision = (new  Valet())->onLatestVerision();
    return response()->json(['onLatestVerision' => $onLatestVerision]);
});

Route::get('run', function () {
    $list = (new  Valet())->run();
    return response()->json($list);
});

Route::post('link', function (Request $request) {
    $link = (new  Valet())->link($request->path, $request->name)->save();
    return response()->json($link);
});

Route::post('unlink', function (Request $request) {
    $done = (new  Valet())->unlink($request->name);
    return response()->json(['status' => $done]);
});
