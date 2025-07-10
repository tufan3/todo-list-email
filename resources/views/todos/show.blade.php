@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ $todo->title }}</h4>
                <div class="btn-group">
                    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-primary">Edit</a>
                    @if(!$todo->completed)
                        <form action="{{ route('todos.complete', $todo) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Mark Complete</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $todo->description ?: 'No description provided.' }}</p>
                </div>

                <div class="mb-3">
                    <strong>Due Date:</strong>
                    <p class="{{ $todo->due_date && !$todo->completed ? 'text-danger' : 'text-muted' }}">
                        {{ $todo->due_date }}
                        {{-- ({{ $todo->due_date->diffForHumans() }}) --}}
                    </p>
                </div>

                <div class="mb-3">
                    <strong>Status:</strong>
                    @if($todo->completed)
                        <span class="badge bg-success">Completed</span>
                    @elseif($todo->due_date && $todo->due_date < now())
                        <span class="badge bg-danger">Overdue</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </div>

                @if($todo->reminder_sent)
                    <div class="mb-3">
                        <span class="badge bg-info">Email Reminder Sent</span>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('todos.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection