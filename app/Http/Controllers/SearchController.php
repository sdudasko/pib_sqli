<?php

namespace App\Http\Controllers;

use App\ApiConnectors\ApiConnector;
use App\Models\Food;
use App\Services\DataRetrievalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Unsplash\Search;


class SearchController extends Controller
{
    private $dataRetrievalService;

    protected $perPage = 50;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        $this->dataRetrievalService = $dataRetrievalService;
    }

    public function index()
    {
        (new ApiConnector())->connectToUnsplash();

        DB::table('foods')->truncate();

        $usi = $this->dataRetrievalService->retrieveInput();

        if (!$usi['search']) return view('frontend.search-results', [ 'items' => [], ]);

        $orientation = $usi['orientation'] ? $usi['orientation'] : '';
        $page = $usi['page'] ? $usi['page'] : 1;
        $per_page = $this->perPage;

        if ($orientation) $photos = Search::photos($usi['search'], $page, $per_page, $orientation);
        else $photos = Search::photos($usi['search'], $page, $per_page);

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

        if ($usi['orderBy']) {
            if (!$usi['direction'] || $usi['direction'] == 'asc') {
                $items = $items->sortBy($usi['orderBy']);

            } else {
                $items = $items->sortByDesc($usi['orderBy']);
            }
        }

        $paginatedItems = collect($items)->paginate(15);
        
        return view('frontend.search-results', [
            'items' => $paginatedItems,
            'links' => $paginatedItems->appends(request()->input())->links(),
        ]);
    }
}
