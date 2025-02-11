@extends('layouts.app')

@section('title', 'List | Revision of Ground Rent')

@section('content')
<link rel="stylesheet" href="{{asset('assets/css/rgr.css')}}">

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">RGR</div>
        @include('include.partials.breadcrumbs')
</div>
<!--end breadcrumb-->

<hr>


<div class="card">
    <div class="card-body">
        <div class="row">
            @include('include.parts.rgr-list',['data'=>$data,'highlighted'=>$highlighted])
        </div>
    </div>
</div>

@endsection