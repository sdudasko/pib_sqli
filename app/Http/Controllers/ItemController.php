<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function create()
    {
        return view('frontend.create');
    }

    public function store(Request $request)
    {
        dd('here');
        // Validate the inputs
        $request->validate([
            'name' => 'required',
        ]);

        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png' // Only allow .jpg, .bmp and .png file types.
            ]);

            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->file->store('product', 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            $product = new Product([
                "name" => $request->get('name'),
                "file_path" => $request->file->hashName()
            ]);
            $product->save(); // Finally, save the record.
        }

        return view('products.create');

    }
}
