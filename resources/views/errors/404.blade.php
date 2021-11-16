@extends('frontend.layouts.master')

@section('head')
<title>404</title>
@endsection


@section('content')
<section class="container">
    <div class="mt-100 pb-80 text-center">
        <h1> 404</h1>

        <h2 class="mt-10">
            {{ $exception->getMessage() ?: 'Page not found' }}
        </h2>

        <div class="mt-40">
            <a href="/" class="btn btn-lg btn-rentals elevated bold">Accueil</a>
        </div>
    </div>
</section>

@endsection
