@extends('layouts.app')

@section('title', 'Club Membership List')

@section('content')

    <style>
        div.dt-buttons {
            float: none !important;
            width: 19%;
        }

        div.dt-buttons.btn-group {
            margin-bottom: 20px;
        }

        div.dt-buttons.btn-group .btn {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 4px;
        }

        /* Ensure responsiveness on smaller screens */
        @media (max-width: 768px) {
            div.dt-buttons.btn-group {
                flex-direction: column;
                align-items: flex-start;
            }

            div.dt-buttons.btn-group .btn {
                width: 100%;
                text-align: left;
            }
        }
    </style>
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Club Membership</div>
        @include('include.partials.breadcrumbs')
    </div>
    <!--breadcrumb-->
    <hr>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between py-3">
                <h6 class="mb-0 text-uppercase tabular-record_font align-self-end"></h6>
                <a href="{{ route('create.club.membership.form') }}"><button class="btn btn-primary">+ Add Details</button></a>
            </div>
            <table id="example" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Membership</th>
                        <th>Designation</th>
                        <th>Service</th>
                        <th>Allotment</th>
                        <th>Application Date</th>
                        <th>Joining Date</th>
                        <th>Pay Scale</th>
                        <th>Other Info.</th>
                        <th>
                            <div style="width: 110px; overflow: hidden;">
                                <select class="form-control form-select form-select-sm" name="status" id="status"
                                    style="font-weight: bold;">
                                    <option value="">Status</option>
                                    @foreach ($items as $item)
                                        <option class="text-capitalize" value="{{ $item->id }}" @if ($getStatusId == $item->id)
                                            @selected(true)
                                        @endif>{{ $item->item_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('footerScript')
    <script type="text/javascript">
       $(document).ready(function() {
    var table = $('#example').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: "{{ route('get.club.membership.list') }}",
            data: function(d) {
                d.status = $('#status').val(); // Capture the status filter value
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'category', name: 'category' },
            { data: 'club_type', name: 'club_type' },
            { data: 'designation', name: 'designation' },
            { data: 'name_of_service', name: 'name_of_service' },
            { data: 'year_of_allotment', name: 'year_of_allotment' },
            { data: 'date_of_application', name: 'date_of_application' },
            { data: 'date_of_joining_central_deputation', name: 'date_of_joining_central_deputation' },
            { data: 'pay_scale', name: 'pay_scale' },
            { data: 'other_relevant_information', name: 'other_relevant_information' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        scrollX: true,
        scrollY: '400px',
        scrollCollapse: true,
        dom: '<"top"Blf>rt<"bottom"ip><"clear">',
        buttons: ['csv', 'excel'],
    });

    // Fix: Trigger table reload on status filter change
    $('#status').change(function() {
        table.ajax.reload();
    });
});

    </script>
@endsection
