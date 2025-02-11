@extends('layouts.app')

@section('title', 'Edit Role')

@section('content') 
        <!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Settings</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item">Application Configuration</li>
                <li class="breadcrumb-item">Roles</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
            </ol>
        </nav>
    </div>
    <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
</div>
<!--end breadcrumb-->
<hr>
    <div>
            <div class="col pt-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0 text-uppercase tabular-record_font pb-4">Edit role details</h6>
                            <form action="{{ url('roles/'.$role->id) }}" method="POST" >
                            @csrf
                            @method('PUT')
                                <div class="row align-items-end">
                                        <div class="col-12 col-lg-4">
                                            <label for="role_name" class="form-label">Role Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $role->name }}" placeholder="Role Name" required>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                            </form>

                        
                                    
                    </div>
                </div>
            </div>
        
            
    </div>

@endsection