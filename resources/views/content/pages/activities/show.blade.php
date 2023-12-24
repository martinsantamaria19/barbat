@extends('layouts/layoutMaster')

@section('title', 'Actividad del Envío')

@section('content')
<div class="container">
    <h1>Actividades del Envío</h1>
    <ul>
        @foreach($activities as $activity)
            <li>
                Estado: {{ $activity->status }} - Actualizado por: {{ $activity->user->name }} en {{ $activity->updated_at }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
