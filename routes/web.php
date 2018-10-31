<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

$router->get('/', function () {
    return view('index');
});

$router->post('/domains', function (Request $request) {
    $name = $request->input('address');
    $validator = Validator::make($request->all(), ['address' => 'active_url']);
    if ($validator->fails()) {
        $errors = $validator->errors();
        return view('index', ['error' => $errors->first('address')]);
    }
    $time = Carbon::now()->toRfc2822String();
    $id = DB::table('domains')->insertGetId(
        [
            'name' => $name,
            'created_at' => $time,
            'updated_at' => $time
        ]
    );
    return redirect()->route('domains.show', ['id' => $id]);
});

$router->get('domains/{id}', ['as' => 'domains.show', function ($id) {
    $address = DB::select('select * from domains where id = ?', [$id]);
    return view('domain', ['domain' => $address[0]]);
}]);
