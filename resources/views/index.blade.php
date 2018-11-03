<?php ?>
@extends('layouts.app')

@section('content')
<div class="jumbotron">
        <h1 class="display-4">Analyzer</h1>
        <p class="lead">This service will analize your webpage.</p>
        <hr class="my-4">
        <p>Enter URL for test:</p>
        <p>
        <form action="{{ route('domains.save') }}" method="POST" class="form-inline">
            <div class="form-group mx-sm-1">
                <input type="text" name="address" id="url" required class="form-control" placeholder="https://">
            </div>
            <button type="submit" class="btn btn-primary">Go</button>
        </form>
        </p>
</div>
@if ($error ?? null)
<div class="alert alert-danger" role="alert">
    <?= $error ?>
</div>
@endif
@endsection

 
