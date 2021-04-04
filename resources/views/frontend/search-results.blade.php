@php
    function mergeToUrl($param, $recognizeDirection = false, $direction = 'asc') {
        if ($recognizeDirection) {
            if (request()->has('direction')) {
                request()->get('direction') == 'asc' ? $direction = 'desc' : $direction = 'asc';
            } else {
                $direction = 'asc';
            }
        }
        return url()->current().'?'.http_build_query(array_merge(request()->all(),['orderBy' => $param, 'direction' => $direction]));
    }

    function mergeWhereToUrl($param) {

        return url()->current().'?'.http_build_query(array_merge(request()->all(),['user' => $param, 'page' => 1]));
    }
@endphp

@extends('frontend.layout.main')

@section('content')

    <div class="search-results">

        <div class="flex flex-col" style="width: 1200px; margin: 0 auto">

            @include('frontend.components.search')
            @include('frontend.components.filter')


            @if (!collect($items)->isEmpty())
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ mergeToUrl('price', true) }}">Price</a>

                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ mergeToUrl('title', true) }}">Title</a>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a>Availability</a>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ mergeToUrl('likes', true) }}">Likes</a>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="">Author</a>
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 ">
                                                    @if (!$item->file_path)
                                                        <img class=""
                                                             src="{{ $item->url }}"
                                                             alt="" style="max-width: 450px; width: 80%">
                                                    @else
                                                        <img class=""
                                                             src="{{ asset("storage/food/$item->file_path") }}"
                                                             alt="" style="max-width: 450px; width: 80%">
                                                    @endif
                                                </div>

                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->description }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ sprintf('%01.2f', $item->price) .'$' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->title }}</div>
                                            <div class="text-sm text-gray-500"></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Available
                </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                            {{ number_format( $item->likes , 0 , '.' , ' ' ) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                                            <a href="{{ mergeWhereToUrl($item->user_id) }}">{{ $item->first_name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{--<a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>--}}
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="" style="margin: 15px 0">
                    {!! $links !!}
                </div>
            @else
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-2xl sm:truncate"
                    style="text-align: left; width: 80%; margin-left: 2%;">
                    No reults were found for this query! Try to specify a new one.
                </h2>
            @endif
        </div>
    </div>

@endsection

@section('bottomScripts')

@endsection