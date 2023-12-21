@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();

$customizerHidden = ($customizerHidden ?? '');
@endphp

@extends('layouts/commonMaster' )
<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

@section('layoutContent')

<!-- Content -->
@yield('content')
<!--/ Content -->

@endsection
