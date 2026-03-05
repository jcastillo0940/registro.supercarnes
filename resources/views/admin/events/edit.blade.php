@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Editar evento</h2>
    <form action="{{ route('admin.events.update', $event) }}" method="POST">
        @method('PUT')
        @include('admin.events._form')
    </form>
</div>
@endsection
