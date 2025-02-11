@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
    <style>
        .pagination .active a {
            color: #ffffff !important;
        }

        .required-error-message {
            display: none;
        }

        .required-error-message {
            margin-left: -1.5em;
            margin-top: 3px;
        }

        .form-check-inputs[type=checkbox] {
            border-radius: .25em;
        }

        .form-check .form-check-inputs {
            float: left;
            margin-left: -1.5em;
        }

        .form-check-inputs {
            width: 1.5em;
            height: 1.5em;
            margin-top: 0;
        }
        .form-group {
    width: 100%;
    position: relative;
    margin: 0px 0px 12px;
}
.verticle-horizontal-center {
    min-height: 79vh;
    display: grid;
    place-items: center;
}
    </style>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">CHANGE PASSWORD</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
    <hr>
    <div class="container-fluid">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <form method="POST" action="{{ route('password.store') }}">
                                        @csrf
                                        @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                        @endif
                                        @if (session('failure'))
                                        <div class="alert alert-danger">
                                            {{ session('failure') }}
                                        </div>
                                        @endif

                                        <div class="row g-2">
                                            <div class="col-lg-12 col-12">
                                                <div class="form-group form-box">
                                                    <label for="current_password" class="form-label">Current Password<span class="text-danger">*</span></label>
                                                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current Password" maxlength="10">
                                                    <div id="current_passwordError" class="text-danger text-left"><x-input-error :messages="$errors->get('current_password')" class="mt-2" /></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-12">
                                                <div class="form-group form-box">
                                                    <label for="new_password" class="form-label">Current Password<span class="text-danger">*</span></label>
                                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password" maxlength="10">
                                                    <div id="new_passwordError" class="text-danger text-left"><x-input-error :messages="$errors->get('new_password')" class="mt-2" /></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-12">
                                                <div class="form-group form-box">
                                                    <label for="new_password_confirmation" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Confirm Password" maxlength="10">
                                                    <div id="new_password_confirmationError" class="text-danger text-left"><x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <button type="submit" class="btn btn-primary px-4">Change Password</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
      
                </div>
            </div>
        </div>
    </div>


@endsection
