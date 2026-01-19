@extends('main')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                {{ __('Dashboard') }}
            </h2>
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <div class="mt-8 text-2xl">
                    Welcome to your dashboard
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
