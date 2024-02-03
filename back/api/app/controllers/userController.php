<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index() {
        response()->json(User::all());
        return User::all();
    }

    public function store()
    {
        $row = new User;
        $row->column = request()->get('column');
        $row->delete();
    }

    public function show($id)
    {
        //
    }

    public function update($id): void
    {
        $row = User::find($id);
        $row->column = request()->get('column');
        $row->save();
    }

    public function destroy($id): void
    {
        $row = User::find($id);
        $row->delete();
    }
}
