<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DiDom\Document;
use Validator;

class DomainController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function main()
    {
        return view('index');
    }
    public function show($id)
    {
        $domain = DB::table('domains')->where('id', '=', $id)->get();
        return view('domain', ['domains' => $domain]);
    }
    public function save(Request $request)
    {
        $name = $request->input('address');
        $validator = Validator::make($request->all(), ['address' => 'active_url']);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return view('index', ['error' => $errors->first('address')]);
        }
        $res = $this->client->request('GET', $name);
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
    }
    public function showAll()
    {
        $domains = DB::table('domains')->paginate(5);
        return view('domain', ['domains' => $domains]);
    }
}
