<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$router->get('/', function () {
    return view('index');
});

$router->post('/domains', function (Request $request) {
    $name = $request->input('address');
    $this->validate($request, ['address' => 'active_url']);
    $time = Carbon::now()->toRfc2822String();
    DB::insert('insert into domains (name, created_at, updated_at) values (?,?,?)', [$name, $time, $time]);
    return $name;
});
