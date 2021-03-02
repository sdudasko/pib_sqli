<?php

namespace App\Services;

class DataRetrievalService
{

    public function retrieveInput()
    {
        return [
            'search'      => request()->get('q'),
            'orientation' => request()->get('orientation'),
            'page'        => request()->get('page'),
            'orderBy' => request()->get('orderBy'),
            'direction' => request()->get('direction'),
        ];
    }
}