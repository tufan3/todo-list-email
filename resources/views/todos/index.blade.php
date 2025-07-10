@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Todos</h1>
        <a href="{{ route('todos.create') }}" class="btn btn-primary">Add New Todo</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($todos as $todo)
                <tr>
                    <td>{{ $todo->title }}</td>
                    <td>{{ $todo->description }}</td>
                    <td>{{ $todo->due_date }}</td>
                    <td>
                        <a href="{{ route('todos.show', $todo) }}" class="btn btn-sm btn-outline-info">View</a>
                        <a href="{{ route('todos.edit', $todo) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        @if(!$todo->completed)
                        <form action="{{ route('todos.complete', $todo) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                {{ $todo->completed ? 'Mark as Incomplete' : 'Mark as Complete' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-danger">
                    <h4>No todos yet!</h4>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $todos->links() }}
</div>
@endsection