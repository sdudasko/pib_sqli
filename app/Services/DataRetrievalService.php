<?php

namespace App\Services;

class DataRetrievalService
{

    public function retrieveInput()
    {
        $input = [
            'search'          => request()->get('q'),
            'orientation'     => request()->get('orientation'),
            'more_than_likes' => request()->get('more_than_likes'),
            'page'            => request()->get('page'),
            'orderBy'         => request()->get('orderBy'),
            'direction'       => request()->get('direction'),
            'user'            => request()->get('user'),
        ];

        $input['orientation'] = $input['orientation'] ? $input['orientation'] : '';
        $input['page'] = $input['page'] ? $input['page'] : 1;

        return $input;
    }
}