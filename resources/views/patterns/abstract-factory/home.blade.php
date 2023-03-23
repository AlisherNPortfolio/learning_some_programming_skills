@extends('patterns.abstract-factory.app')

@section('title', 'Home page')

@section('content')

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Welcome</h1>
</div>
<div class="text-center">
    <div class="mb-2">
        <a href="/create-store" class="btn btn-lg btn-block btn-outline-primary">Create a store</a>
    </div>
    <div class="mb-2">
        <a href="/create-account" class="btn btn-lg btn-block btn-primary">Create an account</a>
    </div>
</div>
@endsection

