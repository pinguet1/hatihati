<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupsController
{
    public function store()
    {
        request()->validate([
            'name' => ['required', 'max:255'],
        ]);

        $group = Group::create([
            'name' => request('name'),
        ]);

        Auth::user()->groups()->attach($group->id);

        return redirect('/home');
    }
}
