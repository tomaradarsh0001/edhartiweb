@extends('layouts.app')

@section('title', 'Demand view')

@section('content')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Demand</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Demand</li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
</div>

<hr>
<div class="card">
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-lg-12">
                <table class="table table-bordered">
                    <tr>
                        <th>Property</th>
                        <th>Unique Demand Id</th>
                        <th>Financial Year</th>
                        <th>Net Total</th>
                        <th>Balance</th>
                    </tr>
                    <tr>
                        <th>{{$demand->property_known_as}}</th>
                        <th>{{$demand->unique_id}}</th>
                        <th>{{$demand->current_fy}}</th>
                        <th>₹{{customNumFormat($demand->net_total)}}</th>
                        <th>₹{{customNumFormat($demand->balance_amount)}}</th>
                    </tr>
                </table>
                <br>

                <table class="table table-bordered mt-2">
                    <tr>
                        <th>S.No</th>
                        <th>Subhead Name</th>
                        <th>Duration</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Financial Year</th>
                        <th>Remarks</th>
                    </tr>
                    @foreach($demand->demandDetails as $detail)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$detail->subhead_name}}</td>
                        <td>{{ date('d M, Y',strtotime($detail->duration_from)) ?? ''}} - {{date('d M, Y',strtotime($detail->duration_to)) ?? ''}}</td>
                        <td>₹{{customNumFormat($detail->net_total)}}</td>
                        <td>₹{{customNumFormat($detail->balance_amount)}}</td>
                        <td>{{$detail->fy}}</td>
                        <td>{{$detail->remarks}}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{route('applicant.payForDemand',$demand->id)}}">
                    <button type="button" class="btn btn-primary">Procced to pay</button>
                </a>
            </div>
        </div>
    </div>
</div>
@include('include.alerts.ajax-alert')
@endsection


@section('footerScript')
@endsection