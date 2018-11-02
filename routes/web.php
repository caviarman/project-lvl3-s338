<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    $res = app()->make('GuzzleHttp\\Client')->request('GET', $name);
    $code = $res->getStatusCode();
    $body = $res->getBody()->getContents();
    if ($res->getHeader('content-length')) {
        $contentLength = $res->getHeader('content-length')[0];
    } else {
        $contentLength = strlen($body);
    }
    $time = Carbon::now()->toRfc2822String();
    $id = DB::table('domains')->insertGetId(
        [
            'name' => $name,
            'code' => $code,
            'contentLength' => $contentLength,
            'created_at' => $time,
            'updated_at' => $time
        ]
    );
    return redirect()->route('domains.show', ['id' => $id]);
});

$router->get('/domains/{id}', ['as' => 'domains.show', function ($id) {
    $domain = DB::table('domains')->where('id', '=', $id)->get();
    return view('domain', ['domains' => $domain]);
}]);

$router->get('/domains', ['as' => 'domains.all', function () {
    $domains = DB::table('domains')->paginate(5);
    return view('domain', ['domains' => $domains]);
}]);
