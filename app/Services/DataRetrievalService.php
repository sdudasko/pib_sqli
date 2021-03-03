<?php

namespace App\Services;

class DataRetrievalService
{

    public function retrieveInput()
    {
        $input = [
            'search'      => request()->get('q'),
            'orientation' => request()->get('orientation'),
            'page'        => request()->get('page'),
            'orderBy'     => request()->get('orderBy'),
            'direction'   => request()->get('direction'),
        ];

        $input['orientation'] = $input['orientation'] ? $input['orientation'] : '';
        $input['page'] = $input['page'] ? $input['page'] : 1;

        return $input;
    }
}