@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="content-card">
            <div class="content-card-header">
                <h2>Profile Information</h2>
            </div>
            <div class="content-card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h2>Update Password</h2>
            </div>
            <div class="content-card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h2 class="text-rose-600">Delete Account</h2>
            </div>
            <div class="content-card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
