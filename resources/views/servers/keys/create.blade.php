@extends('layouts.app')

@section('content')
    <h3>{{ __('New key') }}</h3>
    <form action="{{ route('servers.keys.store', $server->id) }}" method="post">
        @csrf

        <label>
            <span>{{ __('Key name') }}:</span>
            <input type="text" name="name" required value="{{ old('name') }}">
        </label>

        <button>{{ __('Create') }}</button>
    </form>
@endsection
