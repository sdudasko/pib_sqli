<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Unsplash\Collection;
use Unsplash\HttpClient;
use Unsplash\Search;


class SearchController extends Controller
{
    private $numberOfItems = 20;

    public function index()
    {
        HttpClient::init([
            'applicationId' => 'cQf5Tlh1o7saCE76X1MwuMbxUY0SvB-qY47Y1CIR5RI',
            'secret'        => 'GVw7ETyyN-gtXtxYQnBj7WmDG5M7PYWsfPuk2gkq5xg',
            'callbackUrl'   => 'pib_sqli/oauth/callback',
            'utmSource'     => 'pib_sqli',
        ]);

        DB::table('foods')->truncate();

        $query = request()->get('q');
        if (!$query) {
            $query = "random";
        }

        $search = $query;
        $page = 1;
        $per_page = 15;
        $orientation = 'landscape';

        $photos = Search::photos($search, $page, $per_page, $orientation);

        $items = collect($photos->getResults())->map(function($item) {

            return Food::create([
                'title' => substr($item['description'], 0, 50),
                'price' => rand(1, 100),
                'likes' => $item['likes'],
                'url' => $item['urls']['small'],
            ]);
        });

        return view('frontend.search-results', [
            'items' => $items,
        ]);
    }

    private function fillTable()
    {
//        $faker = \Faker\Factory::create();
//        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
//
//        $generatedNames = collect([]);
//        $name = $this->generateToArr($generatedNames, $faker);
//
//        for($i = 0; $i < $this->numberOfItems; $i++)
//        {
//            DB::table('foods')->insert($name);
//        }

    }

    private function generateToArr($generatedNames, $faker)
    {
        $generatedNames->push($faker->foodName());
        $generatedNames->push($faker->beverageName());
        $generatedNames->push($faker->dairyName());
        $generatedNames->push($faker->vegetableName());
        $generatedNames->push($faker->fruitName());
        $generatedNames->push($faker->meatName());
        $generatedNames->push($faker->sauceName());

        return $generatedNames->random();
    }


}
