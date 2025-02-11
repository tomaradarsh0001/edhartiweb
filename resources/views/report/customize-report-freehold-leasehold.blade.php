@extends('layouts.app')

@section('title', 'Detailed Report')

@section('content')
    <style>
        label.form-check-label {
            margin: auto -0.5rem;
            padding-left: 10px;
        }
    </style>
    {{-- <link rel="stylesheet" href="{{asset('assets/css/rgr.css')}}"> --}}
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Reports</div>
        @include('include.partials.breadcrumbs')
    </div>
    <!--breadcrumb-->
    <!--end breadcrumb-->
    <hr>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="filter-form" method="get" action="{{ route('customizeReport') }}">
                        <input type="hidden" name="export" value="0">
                        <div class="group-row-filters">
                            <div class="d-flex align-items-start w-btn-full">
                                <div class="relative-input mb-3">
                                    <input class="form-check-input" type="radio" name="filter" id="filter"
                                        value="colony_wise_property">
                                    <label class="form-check-label" for="filter">
                                        Colony Wise Property Details
                                    </label>
                                </div>

                                <div class="relative-input mb-3 mx-2">
                                    <input class="form-check-input" type="radio" name="filter" id="filter"
                                        value="colony_wise_lease_and_free_hold_property" checked>
                                    <label class="form-check-label" for="filter">
                                        Colony Wise Lease Hold & Free Hold Property Details
                                    </label>
                                </div>
                                <div class="relative-input mb-3 mx-2">

                                </div>

                            </div>
                            <div class="d-flex justify-content-end w-btn-full">
                                <div class="btn-group-filter">
                                    <button type="button" class="btn btn-secondary px-5 filter-btn"
                                        onclick="resetFilters()">Reset</button>
                                    <button type="submit" class="btn btn-primary px-5 filter-btn">Apply</button>
                                    <button type="button" class="btn btn-info px-5 filter-btn"
                                        id="export-btn">Export</button>
                                </div>
                            </div>
                    </form>

                    <div class="table-responsive mt-2">
                        <table id="example" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Colony Name</th>
                                    <th>Section</th>
                                    <th>Total Property</th>
                                    <th>Lease Hold</th>
                                    <th>Free Hold</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $startNum = $properties->currentPage()
                                        ? ($properties->currentPage() - 1) * $properties->perPage()
                                        : 0;
                                @endphp
                                @forelse($properties as $property)
                                    <tr>
                                        <td>{{ $loop->iteration + $startNum }}</td>
                                        <td>{{ $property->colony_name }}</td>
                                        <td>{{ $property->section_name }}</td>
                                        <td>{{ $property->total_property_count }}</td>
                                        <td>{{ $property->lease_hold }}</td>
                                        <td>{{ $property->free_hold }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13">No Data to Display</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-2">
                        @php
                            $total = $properties->total();
                        @endphp
                        <div class="col-lg-6">Total {{ $total }} {{ $total != 1 ? 'proeperties' : 'proeperty' }} found
                        </div>
                        <div class="col-lg-6">

                            <div style="float: right;">{{ $properties->appends(request()->input())->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

@endsection

@section('footerScript')
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/property-type-subtype-dropdown.js') }}"></script>
    <script>
        $('#export-btn').click(function() {
            $('input[name="export"]').val(1);
            $('button[type="submit"]').click();
            setTimeout(function() {
                $('input[name="export"]').val(0);
            }, 500)
        })

        $('.input-reset-icon').click(function() {
            // debugger;
            var targetElement = $($(this).data('targets'));
            if (targetElement.attr('name').indexOf('[') > -1) {
                targetElement.selectpicker('deselectAll').selectpicker('render');
            } else {
                targetElement.val('')
                targetElement.selectpicker('render');
            }

            if (targetElement ==
                'property_type') { //if filter is property type then also remove property sub type filter and clear dropdown
                $('#prop-sub-type').selectpicker('deselectAll');
                $('#prop-sub-type') /** remove options from property sub type */
                    .find('option')
                    .remove()
                    .end();
            }

        })

        function resetFilters() {
            $('.input-reset-icon').each(function() {
                $(this).click();
            })
        }

        $(document).ready(function() {
            var table = $('#example').DataTable({
                responsive: false,
                searching: false,
                paging: false,
                info: false
            });
        });
    </script>
@endsection
