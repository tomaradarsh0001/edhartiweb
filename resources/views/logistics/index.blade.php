@extends('layouts.app')
@section('title', 'logistic')
@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Logistics</div>
        @include('include.partials.breadcrumbs')
    </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3">
        <div class="col">
            <div class="card radius-10 border-start border-0">
                <div class="card-body">
                    <div class="text-center">
                        <div class="widgets-icons-2 rounded-circle text-white mt-4 m-auto" style="background-color: #E8F5E9;">
                            <img src="assets/images/lease-hold.svg" alt="properties">
                        </div>
                        <div>
                            <p class="mb-0 text-secondary p-3" style="font-size: 18px">Items & Categories</p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <ul class="nav-links">
                            <li><a href="{{ url('logistic/items') }}"><i class='bx bx-chevron-right'></i> Show Items</a>
                            </li>
                            <li><a href="{{ url('logistic/items/add') }}"><i class='bx bx-chevron-right'></i> Add Items</a>
                            </li>
                            <li><a href="{{ url('logistic/category') }}"><i class='bx bx-chevron-right'></i> Show
                                    Category</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0">
                <div class="card-body">
                    <div class="text-center">
                        <div class="widgets-icons-2 rounded-circle text-white m-auto mt-4"
                            style="background-color: #FCE4EC;">
                            <img src="assets/images/freeHold.svg" alt="Free Hold">
                        </div>
                        <div>
                            <p class="mb-0 text-secondary p-3" style="font-size: 18px">Purchase & Issue Items</p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <ul class="nav-links">
                            <li><a href="{{ url('/logistic/purchase') }}"><i class='bx bx-chevron-right'></i> Show
                                    Purchases</a>
                            </li>
                            <li><a href="{{ url('/logistic/requested-items') }}"><i class='bx bx-chevron-right'></i> Show
                                    Requested Items</a>
                            </li>
                            <li><a href="{{ url('/logistic/issued-item') }}"><i class='bx bx-chevron-right'></i> Issue an
                                    Item</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0">
                <div class="card-body">
                    <div class="text-center">
                        <div class="widgets-icons-2 rounded-circle text-white m-auto m-auto mt-4"
                            style="background-color: #E0F2F1;">
                            <img src="assets/images/residential.svg" alt="properties">
                        </div>
                        <div>
                            <p class="mb-0 text-secondary p-3" style="font-size: 18px">Vendor & Stock Records</p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <ul class="nav-links">
                            <li><a href="{{ url('/logistic/vendor') }}"><i class='bx bx-chevron-right'></i> Show
                                    Vendors</a>
                            </li>
                            <li><a href="{{ url('/logistic/stock') }}"><i class='bx bx-chevron-right'></i>
                                    Available Stocks</a>
                            </li>
                            <li><a href="{{ url('/logistic/history') }}"><i class='bx bx-chevron-right'></i> Stock
                                    History</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
