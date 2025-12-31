<h1>{{$group->name}}</h1>

<section>
    <h2>People in {{ $group->name }}</h2>

    <ul>
        @foreach($users as $user)
            <li>
                {{ $user->name ?? $user->email }}
            </li>
        @endforeach
    </ul>

    <details>
        <summary>
            Invite a new member
        </summary>

        <form method="POST" action="/groups/{{ $group->id }}/people">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" />

            <button>Invite</button>
        </form>
    </details>
</section>

<section>
    <h2>Add a new expense for this group</h2>

    <form action="/expenses" method="POST">
        @csrf

        <label for="description">Description</label>
        <input id="description" type="text" name="description">

        <label for="amount">Expense</label>
        <input id="amount" type="number" name="amount">

        <button>Add Expense</button>
    </form>
</section>
