<?php

namespace Tests\Feature\GroupsController;

use App\Models\Group;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();

    actingAs($this->user);
});

it('creates a group', function () {
    assertDatabaseCount('groups', 0);
    assertDatabaseCount('group_user', 0);

    createGroup([
        'name' => 'Discord Peeps',
    ])->assertRedirect('/home');

    assertDatabaseCount('groups', 1);
    assertDatabaseCount('group_user', 1);

    assertDatabaseHas('groups', [
        'name' => 'Discord Peeps',
    ]);

    assertDatabaseHas('group_user', [
        'user_id' => $this->user->id,
        'group_id' => Group::first()->id,
    ]);
});

it('validates the data', function ($attribute, $value, $message) {
    createGroup([
        $attribute => $value,
    ])->assertInvalid([
        $attribute => $message,
    ]);
})->with([
    'name is required' => ['name', null, 'The name field is required.'],
    'name must be less than 255 characters' => ['name', str_repeat('a', 300), 'The name field must not be greater than 255 characters.'],
]);

function createGroup($attributes = [])
{
    return test()->post(
        route('groups.store'),
        $attributes
    );
}
