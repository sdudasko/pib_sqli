<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Unsplash\HttpClient;
use Unsplash\Search;


class SearchController extends Controller
{
    public function index()
    {
        HttpClient::init([
            'applicationId' => env('UNSPLASH_APP_ID'),
            'secret'        => env('UNSPLASH_APP_SECRET'),
            'callbackUrl'   => 'pib_sqli/oauth/callback',
            'utmSource'     => 'pib_sqli',
        ]);

        DB::table('foods')->truncate();

        $query = request()->get('q');

        if (!$query) {
            return view('frontend.search-results', [
                'items' => [],
            ]);
        }

        $orientation = request()->get('orientation');
        $page = request()->get('page');
        $search = $query;
        $page = $page ? 1 : $page;
        $per_page = 50;
        $orientation = $orientation ? $orientation : '';

        if ($orientation) $photos = Search::photos($search, $page, $per_page, $orientation);
        else $photos = Search::photos($search, $page, $per_page);

        $items = collect($photos->getResults())->map(function ($item) { // TODO - iterate pages
            $foodItem = Food::where('unsplash_id', $item['id'])->first();
            if ($foodItem) {
                return $foodItem;
            }
            try {
                return Food::create([
                    'unsplash_id' => $item['id'],
                    'title'       => Str::limit($item['description'], 50, $limit = '...'),
                    'price'       => rand(1, 100),
                    'likes'       => $item['likes'],
                    'url'         => $item['urls']['small'],
                ]);
            } catch (\Exception $exception) {
                throw $exception;
            }

        });

        $paginatedItems = collect($items)->paginate(15);
        return view('frontend.search-results', [
            'items' => $paginatedItems,
            'links' => $paginatedItems->appends(request()->input())->links(),
        ]);
    }

}
