<?php

namespace Tests\Feature\GroupUsersController;

use App\Mail\Invite;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = User::factory()->create();

    actingAs($this->user);

    Mail::fake();
});

it('add an existing user to a group', function () {
    $mich = User::factory()->create([
        'email' => 'mich@bayadbayad.com',
    ]);

    $group = Group::factory()->create();

    $group->users()->attach($this->user);

    assertDatabaseCount('group_user', 1);
    assertDatabaseCount('users', 2);

    addPersonToGroup($group, [
        'email' => 'mich@bayadbayad.com',
    ]);

    assertDatabaseCount('group_user', 2);
    assertDatabaseCount('users', 2);

    assertDatabaseHas('group_user', [
        'user_id' => $mich->id,
        'group_id' => $group->id,
    ]);
});

it('add a new user to a group', function () {
    $group = Group::factory()->create();

    $group->users()->attach($this->user);

    assertDatabaseCount('group_user', 1);
    assertDatabaseCount('users', 1);

    addPersonToGroup($group, [
        'email' => 'mich@bayadbayad.com',
    ]);

    Mail::assertSent(function (Invite $invite) {
        return $invite->hasTo('mich@bayadbayad.com');
    });

    assertDatabaseCount('group_user', 2);
    assertDatabaseCount('users', 2);

    assertDatabaseHas('users', [
        'name' => null,
        'email' => 'mich@bayadbayad.com',
        'password' => null,
    ]);
});

it('doesnt allow outside users to invite to the group', function () {
    $group = Group::factory()->create();

    assertDatabaseCount('group_user', 0);

    addPersonToGroup($group, [
        'email' => 'mich@bayadbayad.com',
    ]);

    assertDatabaseCount('group_user', 0);
});

function addPersonToGroup(Group $group, $attributes = [])
{
    return test()->post(
        "/groups/{$group->id}/people",
        $attributes
    );
}
