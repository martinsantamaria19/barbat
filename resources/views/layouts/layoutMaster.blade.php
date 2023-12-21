@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
$configData = Helper::appClasses();
@endphp

<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>




@isset($configData["layout"])
@include((( $configData["layout"] === 'horizontal') ? 'layouts.horizontalLayout' :
(( $configData["layout"] === 'blank') ? 'layouts.blankLayout' :
(($configData["layout"] === 'front') ? 'layouts.layoutFront' : 'layouts.contentNavbarLayout') )))
@endisset

<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
