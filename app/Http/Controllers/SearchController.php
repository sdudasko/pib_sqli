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
        (new ApiConnector())->connectToUnsplash();

//        DB::table('foods')->truncate();

    }

    public function index()
    {
        $usi = $this->dataRetrievalService->retrieveInput();

        if (!$usi['search']) return view('frontend.search-results', ['items' => [],]);
        $per_page = $this->perPage;

        $search = $usi['search'];
        if ($usi['orientation']) $photos = Search::photos($usi['search'], $usi['page'], $per_page, $usi['orientation']);
        else $photos = Search::photos($usi['search'], $usi['page'], $per_page);

        $items = DB::SELECT("SELECT * FROM foods WHERE id = $search");

//        collect($photos->getResults())->each(function ($item) { // TODO - iterate pages
//            $foodItem = Food::where('unsplash_id', $item['id'])->first();
//            $this->createItem($item);
//        });
//        dd($usi);



//        $items = Food::orderBy($usi['search'])->get();

//        dd(DB::getQueryLog()); // Show results of log

//        http://pib_sqli.test/sqli?q=id-%3Etest%22%27+ASC%2C+IF%28%28SELECT+count%28*%29+FROM+users++%29+%3C+10%2C+SLEEP%2820%29%2C+SLEEP%280%29%29+DESC++--++%22%27
//        http://example.com/users?orderBy=id->test"' ASC, IF((SELECT count(*) FROM users  ) < 10, SLEEP(20), SLEEP(0)) DESC  --  "'


//        // Vulnerable query
//        if ($usi['orderBy']) {
//            $items = Food::orderBy($usi['orderBy'])->get();
//        } else {
//            $items = Food::all();
//        }


//        if ($usi['orderBy']) {
//            if (!$usi['direction'] || $usi['direction'] == 'asc') $items = $items->sortBy($usi['orderBy']);
//            else $items = $items->sortByDesc($usi['orderBy']);
//        }

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
