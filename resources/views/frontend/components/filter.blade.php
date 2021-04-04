<div class="flex rounded-lg filter-row" role="group">
    <div class="">
        <a
                href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['orientation' => 'landscape'])) }}"

                class="bg-white text-purple-500 hover:bg-purple-500 hover:text-white border border-r-0 border-purple-500 rounded-l-lg px-4 py-2 mx-0 outline-none focus:shadow-outline {{ isset($_GET['orientation']) && $_GET['orientation'] == 'landscape' ? ' filter--active' : '' }}">
            Landscape
        </a>
        <a
                href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['orientation' => 'portrait'])) }}"
                class="bg-white text-purple-500 hover:bg-purple-500 hover:text-white border border-purple-500  px-4 py-2 mx-0 outline-none focus:shadow-outline {{ isset($_GET['orientation']) && $_GET['orientation'] == 'portrait' ? ' filter--active' : '' }}">
            Portrait
        </a>
        <a
                href="{{ url()->current().'?'.http_build_query(request()->except("orientation")) }}"
                class="bg-white text-purple-500 hover:bg-purple-500 hover:text-white border border-purple-500  px-4 py-2 mx-0 outline-none focus:shadow-outline {{ !isset($_GET['orientation']) ? ' filter--active' : '' }}">
            All resolutions
        </a>

        <a
                href="{{  url()->current().'?'.http_build_query(array_merge(request()->all(),['more_than_likes' => 100]))  }}"
                class="bg-white text-purple-500 hover:bg-purple-500 hover:text-white border border-l-0 border-purple-500 rounded-r-lg px-4 py-2 mx-0 outline-none focus:shadow-outline {{ isset($_GET['more_than_likes']) ? ' filter--active' : '' }}">
            More than 100 likes
        </a>
    </div>

    <div class="filter-cta-container">
        <div class="">
            <a href="{{ route('create') }}">
                <button class="plus-button nav-button"></button>
            </a>
        </div>
        <div class="create-container">
            <a href="{{ route('search') }}">
                <button class="plus-button nav-button">
                    <i class="fa fa-long-arrow-alt-left"></i>
                </button>
            </a>
        </div>
    </div>
</div>
