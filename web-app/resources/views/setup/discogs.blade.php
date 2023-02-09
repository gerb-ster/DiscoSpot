@extends('layouts.app')

@section('content')
    <div class="container mx-auto text-center">
        <h4 class="mb-4 mt-5 text-2xl font-extrabold leading-none tracking-tight text-gray-900 md:text-2xl lg:text-2xl dark:text-white ">
            First, you'll need to connect your Discogs account
        </h4>
        <a href="{{ route('auth.discogs.connect') }}" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
            Click here to connect to Discogs
        </a>
    </div>
@stop
