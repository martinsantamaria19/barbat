@extends('layouts/layoutMaster')

@section('title', 'Actividad del Paquete')

@section('content')
<div class="container">
    <h1>Actividades del Paquete</h1>
    <ul>
        @foreach($activities as $activity)
            <li>
                Estado: {{ $activity->status }} - Actualizado por: {{ $activity->user->name }} en {{ $activity->updated_at }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
