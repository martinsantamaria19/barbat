@extends('layouts/layoutMaster')

@section('content')
    <div class="container">
        <h1>Listado de Permisos</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Permiso</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
