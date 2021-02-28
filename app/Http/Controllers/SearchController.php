<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Unsplash\Collection;
use Unsplash\HttpClient;
use Unsplash\Search;

class SearchController extends Controller
{
    public function index()
    {
        HttpClient::init([
            'applicationId'	=> 'cQf5Tlh1o7saCE76X1MwuMbxUY0SvB-qY47Y1CIR5RI',
            'secret'	=> 'GVw7ETyyN-gtXtxYQnBj7WmDG5M7PYWsfPuk2gkq5xg',
            'callbackUrl'	=> 'pib_sqli/oauth/callback',
            'utmSource' => 'pib_sqli'
        ]);

        $search = 'brazilian coffee';
        $page = 3;
        $per_page = 15;
        $orientation = 'landscape';

        $photos = Search::collections($search, $page, $per_page);

        return view('frontend.search-results', [
            'photos' => $photos->getResults()
        ]);

    }
}
