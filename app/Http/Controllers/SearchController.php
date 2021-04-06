<?php

namespace App\Http\Controllers;

use App\ApiConnectors\ApiConnector;
use App\Models\Photo;
use App\Models\Name;
use App\Models\User;
use App\Services\DataRetrievalService;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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

        $this->queryBuilder = Photo::query();

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index()
    {
//        DB::enableQueryLog();
//        DB::table('photos')->truncate();
        $usi = $this->dataRetrievalService->retrieveInput();

//        if (!$usi['search']) return view('frontend.search-results', ['items' => [],]);
        $per_page = $this->perPage;

        !$usi['search'] ? $usi['search'] = 'cat' : $usi['search'];

        $search = $usi['search'];


////      1. Nezabezpočená query
//        $this->queryBuilder = $this->queryBuilder->whereRaw(
//            "title like '%$search%'"
//        );
////      1. Sposôb ochrany
//        $this->queryBuilder = $this->queryBuilder->whereRaw(
//            "title like ?", ["%$search%"]
//        );
////      2. Sposôb ochrany
//        $this->queryBuilder = $this->queryBuilder->where(
//            'title', "like", "%$search%"
//        );
////      3. Sposôb ochrany

        $sanitized = Validator::make(request()->all(), [
            'q'    => 'string',
            'user' => 'integer',
        ])->validated();

        if (is_null(Photo::first())) {
            $this->queryBuilder = $this->queryBuilder->where(
                'title', "like", "%$search%"
            );
            if ($usi['orientation'])
                $photos = Search::photos($usi['search'], $usi['page'], $per_page, $usi['orientation']);
            else
                $photos = Search::photos($usi['search'], $usi['page'], $per_page);

            collect($photos->getResults())->each(function ($item) { // TODO - iterate pages
                $this->createItem($item);
            });
        } else {
            //
        }

        DB::enableQueryLog();

        $this->queryBuilder = $this->queryBuilder
            ->leftJoin('users', 'photos.user_id', '=', 'users.id');

        // Vulnerable query
        if (request()->user) {

            $user = User::findOrFail($sanitized['user']);
            $first_name = $user->first_name;

            $this->queryBuilder = $this->queryBuilder
                ->whereRaw("first_name =" . " '$first_name' ");
        }

        // Vulnerable query
        if ($usi['orderBy']) {
//            $this->queryBuilder = $this->queryBuilder->orderBy($usi['orderBy']);
            $this->queryBuilder = $this->queryBuilder->orderByRaw($usi['orderBy']);
//            $items = Photo::orderBy($usi['orderBy'])->get();
        } else {
            $items = Photo::all();
        }

        if (isset($usi['more_than_likes'])) {
            $more_than_likes = $usi['more_than_likes'];

            $this->queryBuilder = $this->queryBuilder->whereRaw("likes > $more_than_likes");
        }

        $items = $this->queryBuilder->get();

        if ($usi['orderBy']) {
            if (!$usi['direction'] || $usi['direction'] == 'asc') $items = $items->sortBy($usi['orderBy']);

            else $items = $items->sortByDesc($usi['orderBy']);
        }

        $items->load('user');

        $paginatedItems = collect($items)->sortBy('id')->paginate(50);

        return view('frontend.search-results', [
            'items' => $paginatedItems,
            'links' => $paginatedItems->appends(request()->input())->links(),
        ]);
    }

    private function createItem($item)
    {
        $user_ids = User::all()->pluck('id');
        try {
            $photo = new Photo([
                'unsplash_id' => $item['id'],
                'title'       => Str::limit($item['description'], 50, $limit = '...'),
                'price'       => rand(1, 100),
                'likes'       => $item['likes'],
                'url'         => $item['urls']['small'],
                'description' => null,
                'user_id'     => $user_ids->random(),
            ]);
            $photo->save();
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
