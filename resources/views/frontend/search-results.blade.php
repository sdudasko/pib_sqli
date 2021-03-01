@extends('frontend.layout.main')

@section('content')

    <div class="">

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
                                        Price
                                    {{--<th scope="col"--}}
                                    {{--class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
                                    {{--Discounted price--}}
                                    {{--</th>--}}
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Likes
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Availability</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 ">
                                                    <img class=""
                                                         src="{{ $item->url }}"
                                                         alt="" style="max-width: 80%">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $item['description'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        {{--<td class="px-6 py-4 whitespace-nowrap">--}}
                                        {{--<div class="text-sm font-medium text-gray-900">--}}
                                        {{--{{ sprintf('%01.2f', $item['price']) .'$' }}--}}
                                        {{--</div>--}}
                                        {{--</td>--}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ sprintf('%01.2f', $item['price']) .'$' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item['title'] }}</div>
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