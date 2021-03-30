<?php

namespace App\Http\Controllers;

use App\ApiConnectors\ApiConnector;
use App\Models\Food;
use App\Services\DataRetrievalService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Unsplash\Search;


class SearchController extends Controller
{
    private $dataRetrievalService;

    protected $perPage = 50;

    protected $queryBuilder;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        $this->dataRetrievalService = $dataRetrievalService;
        (new ApiConnector())->connectToUnsplash();

        $this->queryBuilder = Food::query();
//        DB::table('foods')->truncate();

    }

    public function index()
    {
        DB::enableQueryLog();
        DB::table('foods')->truncate();
        $usi = $this->dataRetrievalService->retrieveInput();

        if (!$usi['search']) return view('frontend.search-results', ['items' => [],]);
        $per_page = $this->perPage;

        $search = $usi['search'];

//      1. Nezabezpočená query
        $this->queryBuilder = $this->queryBuilder->whereRaw(
            "title like '%$search%'"
        );
////      1. Sposôb ochrany
        $this->queryBuilder = $this->queryBuilder->whereRaw(
            "title like ?", ["%$search%"]
        );
////      2. Sposôb ochrany
//        $this->queryBuilder = $this->queryBuilder->where(
//            'title', "like", "%$search%"
//        );
////      3. Sposôb ochrany
        $sanitized = Validator::make(request()->all(), [
            'q' => 'string',
        ])->validated();

        if ($usi['orientation'])
            $photos = Search::photos($usi['search'], $usi['page'], $per_page, $usi['orientation']);
        else
            $photos = Search::photos($usi['search'], $usi['page'], $per_page);

        if (isset($usi['more_than_likes'])) {
            $more_than_likes = $usi['more_than_likes'];

            $this->queryBuilder = $this->queryBuilder->whereRaw("likes > $more_than_likes");
        }

        collect($photos->getResults())->each(function ($item) { // TODO - iterate pages
            $foodItem = Food::where('unsplash_id', $item['id'])->first();
            $this->createItem($item);
        });

        // Vulnerable query
        if ($usi['orderBy']) {
//            $this->queryBuilder = $this->queryBuilder->orderBy($usi['orderBy']);
            $this->queryBuilder = $this->queryBuilder->orderByRaw($usi['orderBy']);
//            $items = Food::orderBy($usi['orderBy'])->get();
        } else {
            $items = Food::all();
        }

        $items = $this->queryBuilder->get();
//        dd(DB::getQueryLog());

        if ($usi['orderBy']) {
            if (!$usi['direction'] || $usi['direction'] == 'asc') $items = $items->sortBy($usi['orderBy']);

            else $items = $items->sortByDesc($usi['orderBy']);
        }

        $paginatedItems = collect($items)->paginate(15);

        return view('frontend.search-results', [
            'items' => $paginatedItems,
            'links' => $paginatedItems->appends(request()->input())->links(),
        ]);
    }

    private function createItem($item)
    {
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

    }
}
