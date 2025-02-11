@extends('layouts.app')
@section('title', 'Club Membership Details')
@section('content')
    <style>
        .pagination .active a {
            color: #ffffff !important;
        }
    </style>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Club Membership</div>
        @include('include.partials.breadcrumbs')
    </div>
    <!-- <div class="ms-auto"><a href="#" class="btn btn-primary">Button</a></div> -->
    <hr>
    <div class="card">
        <div class="card-body">
            <!-- <div class="container"> -->
            <div class="part-title">
                <h5>Application Details</h5>
            </div>
            <div class="part-details">
                <div class="container-fluid">
                    @if (!empty($getClubMembershipDetails->club_type))
                        @if ($getClubMembershipDetails->club_type == 'IHC')
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><b>Membership Type : </b> {{ $getClubMembershipDetails->club_type ?? '' }}</td>
                                        @if (!empty($getClubMembershipDetails->category) && $getClubMembershipDetails->category == 'other')
                                            <td><b>Category : </b> {{ $getClubMembershipDetails->other_category ?? '' }}
                                            </td>
                                        @else
                                            <td><b>Category : </b> {{ $getClubMembershipDetails->category ?? '' }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><b>Application Date : </b>
                                            {{ $getClubMembershipDetails->date_of_application ? \Carbon\Carbon::parse($getClubMembershipDetails->date_of_application)->format('d/m/Y') : '' }}
                                        </td>
                                        <td><b>Name : </b> {{ $getClubMembershipDetails->name ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Designation : </b> {{ $getClubMembershipDetails->designation ?? '' }}</td>
                                        @if (
                                            !empty($getClubMembershipDetails->designation_equivalent_to) &&
                                                $getClubMembershipDetails->designation_equivalent_to == 'OTHER')
                                            <td><b>Equivalent Designation : </b>
                                                {{ $getClubMembershipDetails->other_designation_equivalent_to ?? '' }}</td>
                                        @else
                                            <td><b>Equivalent Designation : </b>
                                                {{ $getClubMembershipDetails->designation_equivalent_to ?? '' }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td><b>Service : </b> {{ $getClubMembershipDetails->name_of_service ?? '' }}</td>
                                        <td><b>Allotment Year : </b>
                                            {{ $getClubMembershipDetails->year_of_allotment ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Date of Joining : </b>
                                            {{ $getClubMembershipDetails->date_of_joining_central_deputation ? \Carbon\Carbon::parse($getClubMembershipDetails->date_of_joining_central_deputation)->format('d/m/Y') : '' }}
                                        </td>
                                        <td><b>Date of Completion : </b>
                                            {{ $getClubMembershipDetails->expected_date_of_tenure_completion ? \Carbon\Carbon::parse($getClubMembershipDetails->expected_date_of_tenure_completion)->format('d/m/Y') : '' }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>Date of Superannuation : </b>
                                            {{ $getClubMembershipDetails->date_of_superannuation ? \Carbon\Carbon::parse($getClubMembershipDetails->date_of_superannuation)->format('d/m/Y') : '' }}
                                        </td>
                                        <td><b>Office Address : </b> {{ $getClubMembershipDetails->office_address ?? '' }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>Email : </b> {{ $getClubMembershipDetails->email ?? '' }}</td>
                                        <td><b>Mobile No. : </b> {{ $getClubMembershipDetails->mobile ?? '' }}</td>

                                    </tr>
                                    <tr>
                                        <td><b>Pay Scale : </b> {{ $getClubMembershipDetails->pay_scale ?? '' }}</td>
                                        <td><b>Individual membership date/relevant details in IHC : </b>
                                            {{ $getClubMembershipDetails->ihcDetails?->individual_membership_date_and_remark }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>Tenure Member Of Delhi Golf Club : </b>
                                            <strong class="text-primary">From :</strong>
                                            {{ $getClubMembershipDetails->ihcDetails?->dgc_tenure_start_date }}
                                            <strong class="text-primary">To :</strong>
                                            {{ $getClubMembershipDetails->ihcDetails?->dgc_tenure_end_date }}
                                        </td>
                                        <td><b>Name Of Present/Previous Membership Of Other Clubs : </b>
                                            {{ $getClubMembershipDetails->present_previous_membership_of_other_clubs ?? '' }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>Other Relevant Information : </b>
                                            {{ $getClubMembershipDetails->other_relevant_information ?? '' }}</td>
                                        <td style="display: flex; align-items: center;">
                                            <b style="margin-right: 5px;">IHC Membership Document :</b>
                                            <a href="{{ asset('storage/' . $getClubMembershipDetails->ihcDetails?->ihcs_doc) }}"
                                                target="_blank" class="text-danger pdf-icons" data-bs-toggle="tooltip"
                                                data-bs-html="true">
                                                <i class="bx bxs-file-pdf fs-4"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><b>Membership Type : </b> {{ $getClubMembershipDetails->club_type ?? '' }}</td>
                                        <td><b>Category : </b> {{ $getClubMembershipDetails->category ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Application Date : </b>
                                            {{ $getClubMembershipDetails->date_of_application ? \Carbon\Carbon::parse($getClubMembershipDetails->date_of_application)->format('d/m/Y') : '' }}
                                        </td>
                                        <td><b>Name : </b> {{ $getClubMembershipDetails->name ?? '' }}</td>

                                    </tr>
                                    <tr>
                                        <td><b>Designation : </b> {{ $getClubMembershipDetails->designation ?? '' }}</td>
                                        <td><b>Equivalent Designation : </b>
                                            {{ $getClubMembershipDetails->designation_equivalent_to ?? '' }}</td>

                                    </tr>
                                    <tr>
                                        <td><b>Service : </b> {{ $getClubMembershipDetails->name_of_service ?? '' }}</td>
                                        <td><b>Allotment Year : </b>
                                            {{ $getClubMembershipDetails->year_of_allotment ?? '' }}</td>

                                    </tr>
                                    <tr>
                                        <td><b>Date of Joining : </b>
                                            {{ $getClubMembershipDetails->date_of_joining_central_deputation ? \Carbon\Carbon::parse($getClubMembershipDetails->date_of_joining_central_deputation)->format('d/m/Y') : '' }}
                                        </td>
                                        <td><b>Date of Completion : </b>
                                            {{ $getClubMembershipDetails->expected_date_of_tenure_completion ? \Carbon\Carbon::parse($getClubMembershipDetails->expected_date_of_tenure_completion)->format('d/m/Y') : '' }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>Date of Superannuation : </b>
                                            {{ $getClubMembershipDetails->date_of_superannuation ? \Carbon\Carbon::parse($getClubMembershipDetails->date_of_superannuation)->format('d/m/Y') : '' }}
                                        </td>
                                        <td><b>Office Address : </b> {{ $getClubMembershipDetails->office_address ?? '' }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><b>Email : </b> {{ $getClubMembershipDetails->email ?? '' }}</td>
                                        <td><b>Mobile No. : </b> {{ $getClubMembershipDetails->mobile ?? '' }}</td>

                                    </tr>
                                    <tr>
                                        <td><b>Pay Scale : </b> {{ $getClubMembershipDetails->pay_scale ?? '' }}</td>
                                        <td><b>Do you occupy the post under the Central Staffing Scheme : </b>
                                            {{ $getClubMembershipDetails->dgcDetails?->is_post_under_central_staffing_scheme }}
                                        </td>


                                    </tr>
                                    <tr>
                                        <td><b>Regular membership date/relevant details in DGC : </b>
                                            {{ $getClubMembershipDetails->dgcDetails?->regular_membership_date_and_remark }}
                                        </td>
                                        <td><b>Tenure Member Of Delhi Golf Club : </b>
                                            <strong class="text-primary">From :</strong>
                                            {{ $getClubMembershipDetails->dgcDetails?->dgc_tenure_start_date }}
                                            <strong class="text-primary">To :</strong>
                                            {{ $getClubMembershipDetails->dgcDetails?->dgc_tenure_end_date }}
                                        </td>



                                    </tr>
                                    <tr>
                                        <td><b>Date of Handicap in Golf : </b>
                                            {{ $getClubMembershipDetails->dgcDetails?->handicap_certification ?? '' }}
                                        </td>
                                        <td><b>Date of Nomination in Member of India Habitat Centre : </b>
                                            {{ $getClubMembershipDetails->dgcDetails?->ihc_nomination_date ?? '' }}
                                        </td>


                                    </tr>
                                    <tr>
                                        <td colspan="2" style="display: flex; align-items: center;">
                                            <b style="margin-right: 5px;">IHC Membership Document :</b>
                                            <a href="{{ asset('storage/' . $getClubMembershipDetails->dgcDetails?->dgcs_doc) }}"
                                                target="_blank" class="text-danger pdf-icons" data-bs-toggle="tooltip"
                                                data-bs-html="true">
                                                <i class="bx bxs-file-pdf fs-4"></i>
                                            </a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        @endif
                    @endif
                </div>
                <div class="container-fluid">
                    <div class="col-lg-12 col-12">
                        <div class="row mb-3">
                            <div class="d-flex justify-content-end">
                                @if ($isActionButtonVisible)
                                    {{-- Check club membership id & status should in New - Lalit (30/01/2025) --}}
                                    @if (!empty($getClubMembershipDetails->id) && !empty($getClubMembershipDetails->status))
                                        @if ($getClubMembershipDetails->status == getServiceType('CM_NEW'))
                                            <button class="btn btn-primary btn-theme update-status mr-5"
                                                data-id="{{ $getClubMembershipDetails->id }}"
                                                data-status="{{ getServiceType('CM_INP') }}">
                                                Verified
                                            </button>
                                            <button type="button" id="rejectButton" class="btn btn-danger">Reject</button>
                                        @elseif ($getClubMembershipDetails->status == getServiceType('CM_INP'))
                                            <button id="membershipAllotmentBtn" class="btn btn-primary btn-theme mr-5"
                                                data-id="{{ $getClubMembershipDetails->id }}"
                                                data-status="{{ getServiceType('CM_PEN') }}">
                                                Membership Allotment
                                            </button>
                                            <button type="button" id="rejectButton" class="btn btn-danger">Reject</button>
                                        @elseif ($getClubMembershipDetails->status == getServiceType('CM_PEN'))
                                            <button class="btn btn-primary btn-theme update-status mr-5"
                                                data-id="{{ $getClubMembershipDetails->id }}"
                                                data-status="{{ getServiceType('CM_APP') }}">
                                                Approve
                                            </button>
                                            <button type="button" id="rejectButton" class="btn btn-danger">Reject</button>
                                        @endif
                                    @endif    
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('include.loader')
    @include('include.alerts.ajax-alert')
    <div class="modal fade" id="rejectClubMembership" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Membership Rejection </h5>
                    <button type="button" class="close" aria-label="Close" data-bs-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-top: 4px; padding-bottom: 4px;">
                    Do you really want to reject this membership application? <br>This process cannot be undone.
                </div>
                <div class="modal-body input-class-reject" style="padding-top: 4px; padding-bottom: 0px;">
                    <label for="rejection">Enter remarks for rejection of this application.</label>
                    <textarea name="remark" id="remark" class="form-control" placeholder="Enter Remarks"></textarea>
                    <div class="text-danger" id="remarkError"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update-status-with-remark"
                        data-id="{{ $getClubMembershipDetails->id }}"
                        data-status="{{ getServiceType('CM_REJ') }}">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="membershipAllotmentModel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Membership Allotment</h5>
                    <button type="button" class="close" aria-label="Close" data-bs-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-top: 4px; padding-bottom: 4px;">
                    Do you really want to approve this membership application?
                </div>
                <div class="modal-body input-class-approve" style="padding-top: 4px; padding-bottom: 0px;">
                    <label for="rejection">Enter Membership Id.</label>
                    <input type="text" class="form-control" name="membership_id" id="membership_id"
                        placeholder="Enter Membership Id">
                    <div class="text-danger" id="membership_idError"></div>
                </div>
                {{-- <div class="modal-body" style="padding-top: 4px; padding-bottom: 0px;">
                    <label for="rejection">Enter remarks for rejection of this application.</label>
                    <textarea name="remarkApproval" id="remarkApproval" class="form-control" placeholder="Enter Remarks"></textarea>
                    <div class="text-danger" id="remarkApprovalError"></div>
                </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update-approve-status-with-remark"
                        data-id="{{ $getClubMembershipDetails->id }}"
                        data-status="{{ getServiceType('CM_PEN') }}">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerScript')
    <script>
        $(document).ready(function() {
            $('#rejectButton').click(function() {
                $('#rejectClubMembership').modal('show');
            });
            $('#membershipAllotmentBtn').click(function() {
                $('#membershipAllotmentModel').modal('show');
            });
        });
        $(document).on("click", ".update-status", function() {
            var clubMembershipId = $(this).data("id");
            var newStatus = $(this).data("status");
            $.ajax({
                url: "{{ route('update.club.membership.status') }}",
                type: "POST",
                data: {
                    id: clubMembershipId,
                    status: newStatus,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $('.loader_container').addClass('d-none');
                            if ($('.results').hasClass('d-none'))
                                $('.results').removeClass('d-none');
                        showSuccess(response.message);
                        // Ensure checkbox is checked and disabled after success
                        setTimeout(function() {
                            window.location.href = response.redirect_url; // Redirect on success
                        }, 1000); // Slight delay to ensure modal is fully hidden
                    } else {
                        $('.loader_container').addClass('d-none');
                            if ($('.results').hasClass('d-none'))
                                $('.results').removeClass('d-none');
                        showError(response.message);
                        // Ensure checkbox is checked and disabled after success
                        setTimeout(function() {
                            window.location.href = response.redirect_url; // Redirect on success
                        }, 1000); // Slight delay to ensure modal is fully hidden
                    }
                }
            });
        });
        $(document).on("click", ".update-status-with-remark", function(e) {
            e.preventDefault();
            let isValid = true;
            var clubMembershipId = $(this).data("id");
            var newStatus = $(this).data("status");
            var remark = $("#remark").val();

            if (!remark || remark.length <= 50) {
                $('#remarkError').text(!remark ? 'Please enter the remark for rejection.' :
                    'Remark should not exceed 50 characters.');
                isValid = false;
                $('#remark').focus();
            }

            if (!isValid) {
                return;
            }

            $.ajax({
                url: "{{ route('update.club.membership.status') }}",
                type: "POST",
                data: {
                    id: clubMembershipId,
                    status: newStatus,
                    remark: remark,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $('#rejectClubMembership').modal('hide');
                        $('.loader_container').addClass('d-none');
                            if ($('.results').hasClass('d-none'))
                                $('.results').removeClass('d-none');
                        showSuccess(response.message);
                        // Ensure checkbox is checked and disabled after success
                        setTimeout(function() {
                            window.location.href = response.redirect_url; // Redirect on success
                        }, 1000); // Slight delay to ensure modal is fully hidden
                    } else {
                        $('#rejectClubMembership').modal('hide');
                        $('.loader_container').addClass('d-none');
                            if ($('.results').hasClass('d-none'))
                                $('.results').removeClass('d-none');
                        showError(response.message);
                        // Ensure checkbox is checked and disabled after success
                        setTimeout(function() {
                            window.location.href = response.redirect_url; // Redirect on success
                        }, 1000); // Slight delay to ensure modal is fully hidden
                        
                    }
                }
            });
        });
        $(document).on("click", ".update-approve-status-with-remark", function(e) {
            e.preventDefault();

            let clubMembershipId = $(this).data("id");
            let newStatus = $(this).data("status");
            let membershipId = $("#membership_id").val().trim();
            // let remark = $("#remarkApproval").val().trim();

            // Clear previous error messages
            $("#membership_idError, #remarkApprovalError").text("");

            // Validation using if-elseif
            if (!membershipId) {
                $("#membership_idError").text("Please enter the membership ID for approval.");
                // $("#membership_id").focus();
            } /*else if (!remark || remark.length <= 50) {
                $('#remarkApprovalError').text(!remark ? 'Please enter the remark for rejection.' :
                    'Remark should not exceed 50 characters.');
                isValid = false;
                $('#remarkApproval').focus();
            }*/ else {
                // Send AJAX request if validation passes
                $.ajax({
                    url: "{{ route('allotment.club.membership') }}",
                    type: "POST",
                    data: {
                        id: clubMembershipId,
                        status: newStatus,
                        membershipId: membershipId,
                        // remark: remark,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded",
                    success: function(response) {
                        if (response.status) {
                            $("#membershipAllotmentModel").modal("hide");
                            $('.loader_container').addClass('d-none');
                            if ($('.results').hasClass('d-none'))
                                $('.results').removeClass('d-none');
                            showSuccess(response.message);

                            setTimeout(function() {
                                window.location.href = response
                                .redirect_url; // Redirect on success
                            }, 1000);
                        } else {
                            $("#membershipAllotmentModel").modal("hide");
                            $('.loader_container').addClass('d-none');
                            if ($('.results').hasClass('d-none'))
                                $('.results').removeClass('d-none');
                            showError(response.message);
                            setTimeout(function() {
                                window.location.href = response
                                .redirect_url; // Redirect on success
                            }, 1000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        showError("Something went wrong. Please try again.");
                    }
                });
            }
        });
    </script>
@endsection
