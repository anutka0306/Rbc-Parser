<?php

namespace App\Http\Controllers;

use DiDom\Document;


use App\News;
use GuzzleHttp\Client;
use Mockery\Exception;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(Request $request){
$inserted_ids = array();
        $is_deleted =  News::query()->delete();
        if($is_deleted > 0) {
            $client = new Client();
            $response = $client->request('GET', 'https://rbc.ru/');
            $response = $response->getBody()->getContents();

            Storage::put('public/page.txt', $response);
            $file = asset('storage/page.txt');
            $document = new Document($file, true);
            $sub_headings = $document->find('.js-news-feed-list .news-feed__item');
            $inputData = array();

            foreach ($sub_headings as $sub_heading) {
                $articles = array();;
                $fullTextLink = $sub_heading->attr('href');
                $sub_heading_text = $sub_heading->find(".news-feed__item__title")[0]->text();
                $articles['title'] = $sub_heading_text;

                $sub_client = new Client();
                $res = $sub_client->request('GET', $fullTextLink);
                $res = $res->getBody()->getContents();
                $fileName = 'text' . round(microtime(true) * 1000);
                Storage::put('public/fullTexts/' . $fileName . '.html', $res);
                $sub_document = new Document(asset('storage/fullTexts/' . $fileName . '.html'), true);
                $sub_image = $sub_document->first(".article__main-image__image");
                if (!empty($sub_image)) {
                    $articles['image'] = $sub_image->attr('src');
                } else {
                    $articles['image'] = null;
                }

                $sub_text = $sub_document->find(".article__text p");

                if (empty(trim(strip_tags(implode(' ', $sub_text))))) {
                    $sub_text = $sub_document->find(".article__text span");
                }

                $sub_text = mb_convert_encoding(implode(' ', $sub_text), 'UTF-8', 'UTF-8');
                $sub_text = preg_replace('/\s{2,}/', ' ', $sub_text);
                $articles['text'] = @iconv("UTF-8", "UTF-8//IGNORE", $sub_text);
                $articles['description'] = @iconv("UTF-8", "UTF-8//IGNORE", strip_tags(mb_substr($sub_text, 0, 200)));

                Storage::delete('public/fullTexts/' . $fileName . '.html');
                $inputData[] = $articles;
                $is_inserted =  News::query()->create($articles);
                $inserted_ids[]= $is_inserted->id;
               

            }

            //$is_inserted =  News::query()->insert($inputData);
            if(!empty($inserted_ids)) {
                return redirect()->route('News');
               // return view('news')->with('news', News::query()->whereIn('id',$inserted_ids)->get());
            }

        }

    }

    public function allnews(){
        return view('news')->with('news', News::query()->get());
    }

    public function show($id){
        $news = News::query()->find($id);

            return view('news-item')->with([
                'news' => $news,
            ]);


    }
}
