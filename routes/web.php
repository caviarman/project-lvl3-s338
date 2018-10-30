<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$router->get('/', function () {
    return view('index');
});

$router->post('/domains', function (Request $request) {
    $id = ((int)(get_object_vars(DB::select('select id from domains order by id desc limit 1')[0]))['id']) + 1;
    $name = $request->input('address');
    $this->validate($request, ['address' => 'active_url']);
    $time = Carbon::now()->toRfc2822String();
    DB::insert('insert into domains (id, name, created_at, updated_at) values (?,?,?,?)', [$id, $name, $time, $time]);
    //$id = DB::table('domains')->insertGetId(
    //    [
    //        'name' => $name,
    //        'created_at' => $time,
    //        'updated_at' => $time
    //    ]
    //);
    redirect()->route('domains.show', ['id' => $id]);
});

$router->get('domains/{id}', ['as' => 'domains.show', function ($id) {
    //$address = get_object_vars(DB::select('select * from domains where id = ?', [$id]));
    return 'sucsess';
}]);
