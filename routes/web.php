<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DiDom\Document;

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
    $document = new Document($body);
    if ($document->has('h1')) {
        $h1 = $document->find('h1')[0]->text();
    } else {
        $h1 = null;
    }
    if ($document->has('meta[name=keywords]')) {
        $keywords = $document->find('meta[name=keywords]')[0]->getAttribute('content');
    } else {
        $keywords = null;
    }
    if ($document->has('meta[name=description]')) {
        $description = $document->find('meta[name=description]')[0]->getAttribute('content');
    } else {
        $description = null;
    }

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
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description,
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
