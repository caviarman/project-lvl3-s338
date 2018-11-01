<?php ?>
@extends('layouts.app')

@section('content')
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Created</th>
      <th scope="col">Updated</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($domains as $domain)
    <tr>
      <th scope="row">{{ $domain->id }}</th>
      <td><a href="{{ route('domains.show', ['id' => $domain->id]) }}">{{ $domain->name }}</a></td>
      <td>{{ $domain->created_at}}</td>
      <td>{{ $domain->updated_at }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@if (count($domains) > 1)
<div>
  <nav aria-label="Pages">
    {{ $domains->links() }}
  </nav>
</div>
@endif
@endsection