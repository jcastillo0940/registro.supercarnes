@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Crear evento</h2>
    <form action="{{ route('admin.events.store') }}" method="POST">
        @include('admin.events._form')
    </form>
</div>
@endsection
