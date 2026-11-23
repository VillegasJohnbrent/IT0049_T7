@extends('layouts.app')
@section('title', $user->exists ? 'Edit User' : 'Create User')
@section('content')
<h1>{{ $user->exists ? 'Edit User' : 'Create User' }}</h1>
<p class="intro">Enter the user's details below.</p>
@if ($errors->any())
<div class="error-summary" role="alert">Please check the fields below.</div>
@endif
<form class="panel record-form" method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($user->exists)
        @method('PUT')
    @endif
    <div class="field">
        <label for="username">Username *</label>
        <input id="username" name="username" type="text" maxlength="255" value="{{ old('username', $user->username) }}" required @error('username') aria-invalid="true" aria-describedby="username-error" @enderror>
        @error('username')<p class="field-error" id="username-error">{{ $message }}</p>@enderror
    </div>
    <div class="field">
        <label for="full_name">Full name *</label>
        <input id="full_name" name="full_name" type="text" maxlength="255" value="{{ old('full_name', $user->full_name) }}" required @error('full_name') aria-invalid="true" aria-describedby="full_name-error" @enderror>
        @error('full_name')<p class="field-error" id="full_name-error">{{ $message }}</p>@enderror
    </div>
    @if ($user->exists)
    <div class="field">
        <label for="avatar">Profile picture (optional)</label>
        <img class="avatar current-avatar" src="{{ $user->avatar ? Storage::disk('public')->url($user->avatar) : asset('images/avatar-placeholder.svg') }}" alt="Current profile picture" width="80" height="80">
        <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png,image/webp,image/gif" aria-describedby="avatar-help @error('avatar') avatar-error @enderror" @error('avatar') aria-invalid="true" @enderror>
        <p class="field-help" id="avatar-help">JPG, PNG, WEBP or GIF, up to 2 MB and 4096 × 4096 pixels. Saved as a 160 × 160 thumbnail. Leave empty to keep the current picture; select the file again after a form error.</p>
        @error('avatar')<p class="field-error" id="avatar-error">{{ $message }}</p>@enderror
    </div>
    @endif
    <div class="form-actions">
        <button type="submit">{{ $user->exists ? 'Save changes' : 'Add user' }}</button>
        <a class="cancel-link" href="{{ route('users.index') }}">Cancel</a>
    </div>
</form>
@endsection
