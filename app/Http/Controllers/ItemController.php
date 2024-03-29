<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function create()
    {
        return view('frontend.create');
    }

    public function store(Request $request)
    {
        // Validate the inputs
        $validator = Validator::make($request->all(), [
            'title'       => 'nullable',
            'price'       => 'nullable',
            'description' => 'nullable',
            'file'        => 'nullable',
            'last_name'   => 'nullable|string',
            'first_name'  => 'required|string',
            'email'       => ['required', 'email', 'string'],
        ]);

        $validator->validate();

        if ($validator->fails()) { abort(422, $validator->errors()); }

        $sanitized = $validator->validated();

        $user = User::where('email', $sanitized['email'])->first();

        if (!$user) {
            $sanitized['password'] = bcrypt(Str::random(8));
            $user = User::create($sanitized);
        } else {
            $user->update($sanitized);
        }

        $photo = new Photo($sanitized);
        $photo->user_id = $user->id;

        if ($request->has('file')) {
            $request->file->store('photo', 'public');
            $photo->file_path = $request->file->hashName();
        }

        $photo->save();

        return redirect()->route('create');

    }
}
