<div class="p-2" style="text-align: center">
    <form action="{{ route('search') }}" method="get"
          class="mb-4 form-inline conf-sidebar__form">
        <input type="text" style="width: 80%" name="q"
               class="relative outline-none rounded py-3 px-3 bg-white shadow text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:shadow-outline"
               placeholder="What do you have in mind?"/>
        <button type="submit" class="rounded bg-purple-500 hover:bg-purple-700 py-2 px-4 text-white"
                style="margin-left: 2%; width: 15%"> Search
        </button>
    </form>
</div>