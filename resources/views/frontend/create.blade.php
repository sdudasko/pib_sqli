@extends('frontend.layout.main')

@section('content')

    <div class=" flex items-center justify-center">

        <form id="form" method="POST" action="{{ route('store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            {{ csrf_field() }}
            <h1 class="block text-gray-700 font-bold mb-2 text-xl text-center">Add new item to the greatest of the
                databases!</h1>
            <br>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="title" id="title" type="text" placeholder="Title" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="Price">
                    Price
                </label>
                <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="price" id="price" type="number" placeholder="Price" required>
            </div>
            <div class="mb-4">

                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Description
                </label>
                <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        name="description" id="description" type="text" placeholder="Description"
                        required></textarea>
            </div>

            <div class="flex items-center justify-center bg-grey-lighter" style="margin-top: 10px; margin-bottom: 10px">
                <label style="width: 100%;" class="create-label w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z"/>
                    </svg>
                    <span class="mt-2 text-base leading-normal">Select a file</span>
                    <input type='file' name="file" class="hidden"/>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="rounded bg-purple-500 hover:bg-purple-700 py-2 px-4 text-white"
                        style="background: purple; margin: 0 auto; margin-top: 15px; width: 150px"> Submit
                </button>
            </div>

        </form>

    </div>
    <script src="https://kit.fontawesome.com/1e268974cb.js" crossorigin="anonymous"></script>
    <script src="assets/js/helpers.js"></script>
    <script src="assets/js/whatsapp.js"></script>

@endsection