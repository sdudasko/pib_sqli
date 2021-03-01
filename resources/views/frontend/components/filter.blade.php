<div class="flex justify-center rounded-lg" role="group"
     style="margin-left: 2%; justify-content: flex-start; margin-bottom: 20px">
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
            class="bg-white text-purple-500 hover:bg-purple-500 hover:text-white border border-l-0 border-purple-500 rounded-r-lg px-4 py-2 mx-0 outline-none focus:shadow-outline {{ !isset($_GET['orientation']) ? ' filter--active' : '' }}">
        All resolutions
    </a>
</div>