@extends('layouts.app')
@section('title', 'Add Club Membership Details')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Club Membership</div>
        @include('include.partials.breadcrumbs')
    </div>
    <!--breadcrumb-->
    <div class="card shadow-sm mb-4">
        <form action="{{ route('update.club.membership.form') }}" method="POST" enctype="multipart/form-data"
            id="clubMembershipForm">
            @csrf
            <input type="hidden" name="clubMembershipId" id="clubMembershipId" value="{{ $getClubMembershipDetails->id }}">
            <div class="card-body">
                <div class="part-title">
                    <h5>Club Membership Details</h5>
                </div>
                <div class="part-details">
                    <div class="container-fluid">
                        <div class="col-lg-12 col-12">
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="club_type" class="form-label">Club Membership Type<span
                                            class="text-danger">*</span></label>
                                    <select name="club_type" id="club_type" class="form-select">
                                        @if ($getClubMembershipDetails->club_type == 'IHC')
                                            <option value="IHC" selected>India Habitat Centre</option>
                                        @else
                                            <option value="DGC" selected>Delhi Golf Club</option>
                                        @endif
                                    </select>
                                    @error('club_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="club_typeError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="category" class="form-label">Category<span
                                            class="text-danger">*</span></label>
                                    @if ($getClubMembershipDetails->club_type == 'IHC')
                                        <select name="category" id="category" class="form-select">
                                            <option value="secretary/spl. secretary/additional secretary and equivalent"
                                                @if ($getClubMembershipDetails->category == 'secretary/spl. secretary/additional secretary and equivalent') selected @endif>
                                                Secretary/Spl. Secretary/Additional Secretary and equivalent</option>
                                            <option value="joint secretaries / directors and equivalent"
                                                @if ($getClubMembershipDetails->category == 'joint secretaries / directors and equivalent') selected @endif>Joint Secretaries /
                                                Directors and equivalent</option>
                                            <option value="other" @if ($getClubMembershipDetails->category == 'other') selected @endif>Other
                                            </option>
                                        </select>
                                    @else
                                        <select name="category" id="category" class="form-select">
                                            <option value="secretary/ special secretary and equivalent"
                                                @if ($getClubMembershipDetails->category == 'secretary/ special secretary and equivalent') selected @endif>Secretary/ Special
                                                Secretary and equivalent</option>
                                            <option value="additional secretary and equivalent"
                                                @if ($getClubMembershipDetails->category == 'additional secretary and equivalent') selected @endif>Additional Secretary and
                                                equivalent</option>
                                            <option value="joint secretary and equivalent"
                                                @if ($getClubMembershipDetails->category == 'joint secretary and equivalent') selected @endif>Joint Secretary and
                                                equivalent
                                            </option>
                                            <option value="director and equivalent"
                                                @if ($getClubMembershipDetails->category == 'director and equivalent') selected @endif>Director and equivalent
                                            </option>
                                            <option value="other" @if ($getClubMembershipDetails->category == 'other') selected @endif>Other
                                            </option>
                                        </select>
                                    @endif
                                    @error('category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="categoryError"></div>
                                </div>
                                <div class="col-lg-4" id="otherCategoryDiv" @style(!empty($getClubMembershipDetails->other_category && $getClubMembershipDetails->category == 'other') ? 'display: block;' : 'display: none;')>
                                    <label for="other_category" class="form-label">Other Category<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="other_category" id="other_category"
                                        placeholder="Enter Other Category"
                                        value="{{ $getClubMembershipDetails->other_category }}" maxlength="50">
                                    @error('other_category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="other_categoryError"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="date_of_application" class="form-label">Date of Application<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date_of_application" class="form-control"
                                        id="date_of_application"
                                        value="{{ $getClubMembershipDetails->date_of_application ?? '' }}">
                                    @error('date_of_application')
                                        <span class="errorMsg">{{ $message }}</span>
                                    @enderror
                                    <div id="date_of_applicationError" class="text-danger"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter Name" value="{{ $getClubMembershipDetails->name ?? '' }}"
                                        maxlength="30">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="nameError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="designation" class="form-label">Designation<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="designation" id="designation"
                                        placeholder="Enter designation"
                                        value="{{ $getClubMembershipDetails->designation ?? '' }}" maxlength="50">
                                    @error('designation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="designationError"></div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="designation_equivalent_to" class="form-label">Equivalent to
                                        Designation<span class="text-danger">*</span></label>
                                    <select name="designation_equivalent_to" id="designation_equivalent_to"
                                        class="form-select">
                                        <option value="">Select</option>
                                        <option value="SEC" @if ($getClubMembershipDetails->designation_equivalent_to == 'SEC') selected @endif>
                                            Secretary
                                        </option>
                                        {{-- <option value="SS" @if (old('designation_equivalent_to') == 'SS') selected @endif>Spl.Sec.
                                        </option> --}}
                                        <option value="AS" @if ($getClubMembershipDetails->designation_equivalent_to == 'AS') selected @endif>AS
                                        </option>
                                        <option value="JS" @if ($getClubMembershipDetails->designation_equivalent_to == 'JS') selected @endif>JS
                                        </option>
                                        <option value="DIR" @if ($getClubMembershipDetails->designation_equivalent_to == 'DIR') selected @endif>Dir.
                                        </option>
                                        <option value="OTHER" @if ($getClubMembershipDetails->designation_equivalent_to == 'OTHER') selected @endif>Other
                                        </option>
                                    </select>
                                    @error('designation_equivalent_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="designation_equivalent_toError"></div>
                                </div>
                                <div class="col-lg-4" id="otherEquivalentDesignationDiv" @style(!empty($getClubMembershipDetails->other_designation_equivalent_to && $getClubMembershipDetails->designation_equivalent_to == 'OTHER') ? 'display: block;' : 'display: none;')>
                                    <label for="other_designation_equivalent_to" class="form-label">Equivalent to Other
                                        Designation<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="other_designation_equivalent_to"
                                        id="other_designation_equivalent_to"
                                        placeholder="Enter Equivalent to Other Designation"
                                        value="{{ $getClubMembershipDetails->other_designation_equivalent_to ?? '' }}"
                                        maxlength="50">
                                    @error('other_designation_equivalent_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="other_designation_equivalent_toError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="name_of_service" class="form-label">Name of Service<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name_of_service"
                                        id="name_of_service" placeholder="Enter Name of Service"
                                        value="{{ $getClubMembershipDetails->name_of_service ?? '' }}">
                                    @error('name_of_service')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="name_of_serviceError"></div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="year_of_allotment" class="form-label">Allotment Year<span
                                            class="text-danger">*</span></label>
                                    <input type="numeric" class="form-control" name="year_of_allotment"
                                        id="year_of_allotment" placeholder="Enter Allotment Year"
                                        value="{{ $getClubMembershipDetails->year_of_allotment ?? '' }}" maxlength="4">
                                    @error('year_of_allotment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="year_of_allotmentError"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="date_of_joining_central_deputation" class="form-label">Date of Joining on
                                        Central Deputation in Delhi<span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_joining_central_deputation" class="form-control"
                                        id="date_of_joining_central_deputation"
                                        value="{{ $getClubMembershipDetails->date_of_joining_central_deputation->format('Y-m-d') ?? '' }}">
                                    @error('date_of_joining_central_deputation')
                                        <span class="errorMsg">{{ $message }}</span>
                                    @enderror
                                    <div id="date_of_joining_central_deputationError" class="text-danger"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="expected_date_of_tenure_completion" class="form-label">Expected Date of
                                        Completion of Tenure<span class="text-danger">*</span></label>
                                    <input type="date" name="expected_date_of_tenure_completion" class="form-control"
                                        id="expected_date_of_tenure_completion"
                                        value="{{ $getClubMembershipDetails->expected_date_of_tenure_completion->format('Y-m-d') ?? '' }}">
                                    @error('expected_date_of_tenure_completion')
                                        <span class="errorMsg">{{ $message }}</span>
                                    @enderror
                                    <div id="expected_date_of_tenure_completionError" class="text-danger"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="date_of_superannuation" class="form-label">Date of Superannuation<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date_of_superannuation" class="form-control"
                                        id="date_of_superannuation"
                                        value="{{ $getClubMembershipDetails->date_of_superannuation->format('Y-m-d') ?? '' }}">
                                    @error('date_of_superannuation')
                                        <span class="errorMsg">{{ $message }}</span>
                                    @enderror
                                    <div id="date_of_superannuationError" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label for="office_address" class="form-label">Office Address<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="office_address" id="office_address"
                                        placeholder="Enter Office Address"
                                        value="{{ $getClubMembershipDetails->office_address ?? '' }}" maxlength="150">
                                    @error('office_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="office_addressError"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="telephone_no" class="form-label">Enter Telephone Number</label>
                                    <input type="text" name="telephone_no" maxlength="10" class="form-control"
                                        id="telephone_no" value="{{ $getClubMembershipDetails->telephone_no ?? '' }}">
                                    @error('telephone_no')
                                        <span class="errorMsg">{{ $message }}</span>
                                    @enderror
                                    <div id="telephone_noError" class="text-danger"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="email" class="form-label">Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter email" value="{{ $getClubMembershipDetails->email ?? '' }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="emailError"></div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="mobile" class="form-label">Mobile Number<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mobile" id="mobile"
                                        placeholder="Enter Mobile Number"
                                        value="{{ $getClubMembershipDetails->mobile ?? '' }}" maxlength="10">
                                    @error('mobile')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="mobileError"></div>
                                </div>
                            </div>

                            <div id="IHC" @style($getClubMembershipDetails->club_type == 'IHC' ? 'display: block;' : 'display: none;')>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="pay_scale_ihc" class="form-label">Pay Scale (Pay Band & Grade
                                            Pay)<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pay_scale_ihc"
                                            id="pay_scale_ihc" placeholder="Enter Pay Scale"
                                            value="{{ $getClubMembershipDetails->pay_scale ?? '' }}" maxlength="50">
                                        @error('pay_scale_ihc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="text-danger" id="pay_scale_ihcError"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="individual_membership_date_and_remark" class="form-label">Whether
                                            Applied for
                                            individual membership in IHC? If so, date/relevant details thereof<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="individual_membership_date_and_remark"
                                            class="form-control" id="individual_membership_date_and_remark"
                                            placeholder="Enter Date/Relevant Details"
                                            value="{{ $getClubMembershipDetails->ihcDetails->individual_membership_date_and_remark ?? '' }}"
                                            maxlength="200">
                                        @error('individual_membership_date_and_remark')
                                            <span class="errorMsg">{{ $message }}</span>
                                        @enderror
                                        <div id="individual_membership_date_and_remarkError" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label for="dgc_tenure_start_date_ihc" class="form-label">Whether Tenure Member
                                            Of
                                            Delhi Golf Club<span class="text-danger">*</span></label>
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label for="dgc_tenure_start_date_ihc" class="form-label">Start
                                                    Date<span class="text-danger">*</span></label>
                                                <input type="date" name="dgc_tenure_start_date_ihc"
                                                    class="form-control" id="dgc_tenure_start_date_ihc"
                                                    value="{{ $getClubMembershipDetails->ihcDetails->dgc_tenure_start_date ?? '' }}">
                                                @error('dgc_tenure_start_date_ihc')
                                                    <span class="errorMsg">{{ $message }}</span>
                                                @enderror
                                                <div id="dgc_tenure_start_date_ihcError" class="text-danger"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="dgc_tenure_end_date_ihc" class="form-label">End
                                                    Date<span class="text-danger">*</span></label>
                                                <input type="date" name="dgc_tenure_end_date_ihc" class="form-control"
                                                    id="dgc_tenure_end_date_ihc"
                                                    value="{{ $getClubMembershipDetails->ihcDetails->dgc_tenure_end_date ?? '' }}">
                                                @error('dgc_tenure_end_date_ihc')
                                                    <span class="errorMsg">{{ $message }}</span>
                                                @enderror
                                                <div id="dgc_tenure_end_date_ihcError" class="text-danger"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="DGC" @style($getClubMembershipDetails->club_type == 'DGC' ? 'display: block;' : 'display: none;')>
                                <div class="row mb-3">
                                    <div class="col-lg-3">
                                        <label for="pay_scale_dgc" class="form-label">Please Indicate Pay Scale and Grade
                                            Pay<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="pay_scale_dgc"
                                            id="pay_scale_dgc" placeholder="Enter Pay Scale"
                                            value="{{ $getClubMembershipDetails->pay_scale ?? '' }}" maxlength="30">
                                        @error('pay_scale_dgc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="text-danger" id="pay_scale_dgcError"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="is_post_under_central_staffing_scheme" class="form-label">Do you
                                            occupy the post under the Central Staffing Scheme<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="is_post_under_central_staffing_scheme"
                                            class="form-control" id="is_post_under_central_staffing_scheme"
                                            placeholder="Enter Yes/No"
                                            value="{{ $getClubMembershipDetails->dgcDetails->is_post_under_central_staffing_scheme ?? '' }}">
                                        @error('is_post_under_central_staffing_scheme')
                                            <span class="errorMsg">{{ $message }}</span>
                                        @enderror
                                        <div id="is_post_under_central_staffing_schemeError" class="text-danger"></div>
                                    </div>
                                    <div class="col-lg-5">
                                        <label for="regular_membership_date_and_remark" class="form-label">Whether applied
                                            to
                                            DGC for regular membership? if so, date/relevant details thereOf.<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="regular_membership_date_and_remark"
                                            class="form-control" id="regular_membership_date_and_remark"
                                            placeholder="Enter Details"
                                            value="{{ $getClubMembershipDetails->dgcDetails->regular_membership_date_and_remark ?? '' }}">
                                        @error('regular_membership_date_and_remark')
                                            <span class="errorMsg">{{ $message }}</span>
                                        @enderror
                                        <div id="regular_membership_date_and_remarkError" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <label for="individual_membership_date" class="form-label">Have you been a tenure
                                            member of Delhi Golf Club earlier. If so, indicate the period.<span
                                                class="text-danger">*</span></label>
                                        <div class="row mb-3">
                                            <div class="col-lg-6">
                                                <label for="dgc_tenure_start_date_dgc" class="form-label">Start
                                                    Date<span class="text-danger">*</span></label>
                                                <input type="date" name="dgc_tenure_start_date_dgc"
                                                    class="form-control" id="dgc_tenure_start_date_dgc"
                                                    value="{{ $getClubMembershipDetails->dgcDetails->dgc_tenure_start_date ?? '' }}">
                                                @error('dgc_tenure_start_date_dgc')
                                                    <span class="errorMsg">{{ $message }}</span>
                                                @enderror
                                                <div id="dgc_tenure_start_date_dgcError" class="text-danger"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="dgc_tenure_end_date_dgc" class="form-label">End
                                                    Date<span class="text-danger">*</span></label>
                                                <input type="date" name="dgc_tenure_end_date_dgc" class="form-control"
                                                    id="dgc_tenure_end_date_dgc"
                                                    value="{{ $getClubMembershipDetails->dgcDetails->dgc_tenure_end_date ?? '' }}">
                                                @error('dgc_tenure_end_date_dgc')
                                                    <span class="errorMsg">{{ $message }}</span>
                                                @enderror
                                                <div id="dgc_tenure_end_date_dgcError" class="text-danger"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <label for="present_previous_membership_of_other_clubs" class="form-label">Current
                                            Handicap in Golf (Along with certification, if any)<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="handicap_certification" class="form-control"
                                            id="handicap_certification"
                                            value="{{ $getClubMembershipDetails->dgcDetails->handicap_certification ?? '' }}"
                                            maxlength="200">
                                        @error('handicap_certification')
                                            <span class="errorMsg">{{ $message }}</span>
                                        @enderror
                                        <div id="handicap_certificationError" class="text-danger"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="present_previous_membership_of_other_clubs" class="form-label">Whether
                                            you are tenure member of india Habitat Centre? If so, indicate the date of
                                            nomination.<span class="text-danger">*</span></label>
                                        <input type="date" name="ihc_nomination_date" class="form-control"
                                            id="ihc_nomination_date"
                                            value="{{ optional($getClubMembershipDetails->dgcDetails->ihc_nomination_date ?? '')->format('Y-m-d') }}">
                                        @error('ihc_nomination_date')
                                            <span class="errorMsg">{{ $message }}</span>
                                        @enderror
                                        <div id="ihc_nomination_dateError" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="present_previous_membership_of_other_clubs" class="form-label">Name Of
                                        Present/Previous Membership Of Other Clubs</label>
                                    <input type="text" class="form-control"
                                        name="present_previous_membership_of_other_clubs"
                                        id="present_previous_membership_of_other_clubs"
                                        placeholder="Enter Name Of Present/Previous Membership Of Other Clubs"
                                        value="{{ $getClubMembershipDetails->present_previous_membership_of_other_clubs ?? '' }}">
                                    @error('present_previous_membership_of_other_clubs')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="present_previous_membership_of_other_clubsError"></div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="other_relevant_information" class="form-label">Any Other Relevant
                                        Information You May Like To Furnish</label>
                                    <input type="text" class="form-control" name="other_relevant_information"
                                        id="other_relevant_information"
                                        placeholder="Enter Any Other Relevant Information You May Like To Furnish"
                                        value="{{ $getClubMembershipDetails->other_relevant_information ?? '' }}"
                                        maxlength="200">
                                    @error('other_relevant_information')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="other_relevant_informationError"></div>
                                </div>
                                <div class="col-lg-4">
                                    {{-- <label for="document" class="form-label">Upload Document<span
                                            class="text-danger">*</span></label> --}}
                                    <label for="document" class="form-label">Upload Document</label>
                                    <input type="file" class="form-control" name="document" id="document"
                                        accept="application/pdf">
                                    @error('document')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="documentError"></div>
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="consent" name="consent"
                                            required>
                                        <label class="form-check-label" for="consent">I, have read guidelines carefully
                                            and want to apply for the membership.
                                        </label>
                                    </div>
                                    @error('consent')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="text-danger" id="consentError"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="part-details">
                    <div class="container-fluid">
                        <div class="col-lg-12 col-12">
                            <div class="row mb-3">
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="updateClubMembershipFormBtn"
                                        class="btn btn-primary btn-theme">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
    </div>
    </div>
    {{-- Dynamic Element --}}
@endsection
@section('footerScript')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let clubType = document.getElementById("club_type");
            let ihcDiv = document.getElementById("IHC");
            let dgcDiv = document.getElementById("DGC");

            function toggleDivs() {
                let selectedValue = clubType.value;
                if (selectedValue === "IHC") {
                    ihcDiv.style.display = "block";
                    dgcDiv.style.display = "none";
                } else if (selectedValue === "DGC") {
                    ihcDiv.style.display = "none";
                    dgcDiv.style.display = "block";
                } else {
                    ihcDiv.style.display = "none";
                    dgcDiv.style.display = "none";
                }
            }

            // Initial call to set correct div visibility on page load
            toggleDivs();

            // Attach event listener to dropdown
            clubType.addEventListener("change", toggleDivs);
        });

        //On Club Type Dropdown change, change the options value of category dropdow for IHC & DGC - LALIT (30/Jan/2025)
        document.getElementById("club_type").addEventListener("change", function() {
            let categoryDropdown = document.getElementById("category");
            categoryDropdown.innerHTML = ""; // Clear existing options
            let club_type = this.value;
            let options = {
                IHC: ["Secretary/Spl. Secretary/Additional Secretary and equivalent",
                    "Joint Secretaries / Directors and equivalent",
                    "Other",
                ],
                DGC: ["Secretary/ Special Secretary and equivalent", "Additional Secretary and equivalent",
                    "Joint Secretary and equivalent", "Director and equivalent",
                    "Other",
                ]
            };
            if (options[club_type]) {
                options[club_type].forEach(item => {
                    let option = document.createElement("option");
                    option.value = item.toLowerCase();
                    option.textContent = item;
                    categoryDropdown.appendChild(option);
                });
            }

            let categoryValue = document.getElementById("category").value;
            if (categoryValue != '' && categoryValue == 'other') {
                $('#otherCategoryDiv').show();
            } else {
                $('#otherCategoryDiv').hide();
            }
        });
        //On Category Dropdown change visible other category input field - LALIT (31/Jan/2025)
        document.getElementById("category").addEventListener("change", function() {
            let categoryValue = this.value;
            if (categoryValue != '' && categoryValue == 'other') {
                $('#otherCategoryDiv').show();
            } else {
                $('#otherCategoryDiv').hide();
            }
        });

        //On Equivalant Designation change visible other equivalant designation input field - LALIT (31/Jan/2025)
        document.getElementById("designation_equivalent_to").addEventListener("change", function() {
            let categoryValue = this.value;
            if (categoryValue != '' && categoryValue == 'OTHER') {
                $('#otherEquivalentDesignationDiv').show();
            } else {
                $('#otherEquivalentDesignationDiv').hide();
            }
        });

        $(document).ready(function() {
            $('#updateClubMembershipFormBtn').click(function(e) {
                e.preventDefault(); // Prevent default form submission

                // Clear previous error messages
                let isValid = true;

                // Validate presentOccupantName
                if (!$('#club_type').val().trim()) {
                    $('#club_typeError').text('Please select club membership type.');
                    isValid = false;
                    $('#club_type').focus();
                } else {
                    $("#club_typeError").text(""); // Clear the error message
                }
                if (!$('#category').val().trim()) {
                    $('#categoryError').text('Please select category.');
                    isValid = false;
                    $('#category').focus();
                } else {
                    $("#categoryError").text(""); // Clear the error message
                }

                // Get the selected value of the dropdown
                var categoryDropdownValue = $('#category').val();
                if (categoryDropdownValue != '' && categoryDropdownValue == 'other') {
                    if (!$('#other_category').val().trim()) {
                        $('#other_categoryError').text('Enter other category.');
                        isValid = false;
                        $('#other_category').focus();
                    } else {
                        $("#other_categoryError").text(""); // Clear the error message
                    }
                }

                if (!$('#date_of_application').val().trim()) {
                    $('#date_of_applicationError').text('Select application date.');
                    isValid = false;
                    $('#date_of_application').focus();
                } else {
                    $("#date_of_applicationError").text(""); // Clear the error message
                }

                if (!$('#name').val().trim()) {
                    $('#nameError').text('Enter the name.');
                    isValid = false;
                    $('#name').focus();
                } else {
                    $("#nameError").text(""); // Clear the error message
                }

                if (!$('#designation').val().trim()) {
                    $('#designationError').text('Enter the designation.');
                    isValid = false;
                    $('#designation').focus();
                } else {
                    $("#designationError").text(""); // Clear the error message
                }

                if (!$('#designation_equivalent_to').val().trim()) {
                    $('#designation_equivalent_toError').text('Please select equivalent designation.');
                    isValid = false;
                    $('#designation_equivalent_to').focus();
                } else {
                    $("#designation_equivalent_toError").text(""); // Clear the error message
                }

                // Get the selected value of the dropdown
                var eqDesignationDropdownValue = $('#designation_equivalent_to').val();
                if (eqDesignationDropdownValue != '' && eqDesignationDropdownValue == 'OTHER') {
                    if (!$('#other_designation_equivalent_to').val().trim()) {
                        $('#other_designation_equivalent_toError').text(
                            'Enter other equivalent designation.');
                        isValid = false;
                        $('#other_designation_equivalent_to').focus();
                    } else {
                        $("#other_designation_equivalent_toError").text(""); // Clear the error message
                    }
                }

                if (!$('#name_of_service').val().trim()) {
                    $('#name_of_serviceError').text('Enter the service name.');
                    isValid = false;
                    $('#name_of_service').focus();
                } else {
                    $("#name_of_serviceError").text(""); // Clear the error message
                }

                if (!$('#year_of_allotment').val().trim()) {
                    $('#year_of_allotmentError').text('Enter the allotment year.');
                    isValid = false;
                    $('#year_of_allotment').focus();
                } else {
                    $("#year_of_allotmentError").text(""); // Clear the error message
                }

                if (!$('#date_of_joining_central_deputation').val().trim()) {
                    $('#date_of_joining_central_deputationError').text('Select joining date.');
                    isValid = false;
                    $('#date_of_joining_central_deputation').focus();
                } else {
                    $("#date_of_joining_central_deputationError").text(""); // Clear the error message
                }

                if (!$('#expected_date_of_tenure_completion').val().trim()) {
                    $('#expected_date_of_tenure_completionError').text('Select completion date.');
                    isValid = false;
                    $('#expected_date_of_tenure_completion').focus();
                } else {
                    $("#expected_date_of_tenure_completionError").text(""); // Clear the error message
                }

                if (!$('#date_of_superannuation').val().trim()) {
                    $('#date_of_superannuationError').text('Select superannuatation date.');
                    isValid = false;
                    $('#date_of_superannuation').focus();
                } else {
                    $("#date_of_superannuationError").text(""); // Clear the error message
                }

                if (!$('#office_address').val().trim()) {
                    $('#office_addressError').text('Enter office address.');
                    isValid = false;
                    $('#office_address').focus();
                } else {
                    $("#office_addressError").text(""); // Clear the error message
                }

                if (!$('#email').val().trim()) {
                    $('#emailError').text('Enter email address.');
                    isValid = false;
                    $('#email').focus();
                } else {
                    $("#emailError").text(""); // Clear the error message
                }

                if (!$('#mobile').val().trim()) {
                    $('#mobileError').text('Enter mobile number.');
                    isValid = false;
                    $('#mobile').focus();
                } else {
                    $("#mobileError").text(""); // Clear the error message
                }

                const selectClubType = document.getElementById('club_type');
                const clubTypeValue = selectClubType.value; // Get the value of the selected option
                if (clubTypeValue != '' && clubTypeValue == 'IHC') {
                    if (!$('#pay_scale_ihc').val().trim()) {
                        $('#pay_scale_ihcError').text('Enter pay scale.');
                        isValid = false;
                        $('#pay_scale_ihc').focus();
                    } else {
                        $("#pay_scale_ihcError").text(""); // Clear the error message
                    }
                    if (!$('#individual_membership_date_and_remark').val().trim()) {
                        $('#individual_membership_date_and_remarkError').text(
                            'Enter individual membership date/details.');
                        isValid = false;
                        $('#individual_membership_date_and_remark').focus();
                    } else {
                        $("#individual_membership_date_and_remarkError").text(
                        ""); // Clear the error message
                    }
                    if (!$('#dgc_tenure_start_date_ihc').val().trim()) {
                        $('#dgc_tenure_start_date_ihcError').text('Select start date of your membership.');
                        isValid = false;
                        $('#dgc_tenure_start_date_ihc').focus();
                    } else {
                        $("#dgc_tenure_start_date_ihcError").text(""); // Clear the error message
                    }
                    if (!$('#dgc_tenure_end_date_ihc').val().trim()) {
                        $('#dgc_tenure_end_date_ihcError').text('Select end date of your membership.');
                        isValid = false;
                        $('#dgc_tenure_end_date_ihc').focus();
                    } else {
                        $("#dgc_tenure_end_date_ihcError").text(""); // Clear the error message
                    }
                    let startDate = new Date(document.getElementById("dgc_tenure_start_date_ihc").value);
                    let endDate = new Date(document.getElementById("dgc_tenure_end_date_ihc").value);
                    if (startDate > endDate) {
                        $('#dgc_tenure_end_date_ihcError').text(
                            'Start Date should not be greater than End Date.');
                        isValid = false;
                        $('#dgc_tenure_end_date_ihc').focus();
                    } else {
                        $("#dgc_tenure_end_date_ihcError").text(""); // Clear the error message
                    }
                }

                if (clubTypeValue != '' && clubTypeValue == 'DGC') {
                    if (!$('#pay_scale_dgc').val().trim()) {
                        $('#pay_scale_dgcError').text('Enter pay scale.');
                        isValid = false;
                        $('#pay_scale_dgc').focus();
                    } else {
                        $("#pay_scale_dgcError").text(""); // Clear the error message
                    }
                    if (!$('#is_post_under_central_staffing_scheme').val().trim()) {
                        $('#is_post_under_central_staffing_schemeError').text(
                            'Enter post under central staff.');
                        isValid = false;
                        $('#is_post_under_central_staffing_scheme').focus();
                    } else {
                        $("#is_post_under_central_staffing_schemeError").text(
                        ""); // Clear the error message
                    }
                    if (!$('#regular_membership_date_and_remark').val().trim()) {
                        $('#regular_membership_date_and_remarkError').text(
                            'Enter regular membership date/details.');
                        isValid = false;
                        $('#regular_membership_date_and_remark').focus();
                    } else {
                        $("#regular_membership_date_and_remarkError").text(""); // Clear the error message
                    }
                    if (!$('#dgc_tenure_start_date_dgc').val().trim()) {
                        $('#dgc_tenure_start_date_dgcError').text('Select start date of your membership.');
                        isValid = false;
                        $('#dgc_tenure_start_date_dgc').focus();
                    } else {
                        $("#dgc_tenure_start_date_dgcError").text(""); // Clear the error message
                    }
                    if (!$('#dgc_tenure_end_date_dgc').val().trim()) {
                        $('#dgc_tenure_end_date_dgcError').text('Select end date of your membership.');
                        isValid = false;
                        $('#dgc_tenure_end_date_dgc').focus();
                    } else {
                        $("#dgc_tenure_end_date_dgcError").text(""); // Clear the error message
                    }

                    if (!$('#handicap_certification').val().trim()) {
                        $('#handicap_certificationError').text('Select date of current handicap.');
                        isValid = false;
                        $('#handicap_certification').focus();
                    } else {
                        $("#handicap_certificationError").text(""); // Clear the error message
                    }
                    if (!$('#ihc_nomination_date').val().trim()) {
                        $('#ihc_nomination_dateError').text('Select nomination date.');
                        isValid = false;
                        $('#ihc_nomination_date').focus();
                    } else {
                        $("#ihc_nomination_dateError").text(""); // Clear the error message
                    }

                    let startDate = new Date(document.getElementById("dgc_tenure_start_date_dgc").value);
                    let endDate = new Date(document.getElementById("dgc_tenure_end_date_dgc").value);
                    if (startDate > endDate) {
                        $('#dgc_tenure_end_date_dgcError').text(
                            'Start Date should not be greater than End Date.');
                        isValid = false;
                        $('#dgc_tenure_end_date_dgc').focus();
                    } else {
                        $("#dgc_tenure_end_date_dgcError").text(""); // Clear the error message
                    }
                }

                /*const fileInput = $('#document')[0]; // Get the file input element
                const file = fileInput.files[0]; // Get the selected file

                // Check if a file is selected
                if (!file) {
                    $('#documentError').text('Please select a file.');
                    isValid = false;
                } else {
                    // Check if the file is a PDF
                    if (file.type !== "application/pdf") {
                        $('#documentError').text('Only PDF files are allowed.');
                        isValid = false;
                        $('#document').focus();
                    }

                    // Check if the file size is greater than 5MB
                    if (file.size > 5 * 1024 * 1024) { // 5MB in bytes
                        $('#documentError').text('File size must not exceed 5MB.');
                        isValid = false;
                        $('#document').focus();
                    }
                }*/

                // if ($("#consent").prop('checked') == false) {
                //     $('#consentError').text('You must accept the terms and conditions.');
                //     isValid = false;
                //     $('#consent').focus();
                // }

                // Prevent form submission if any validation fails
                if (!isValid) {
                    return;
                }
                // Submit the form if valid
                $('#updateClubMembershipFormBtn').prop('disabled', true);
                $('#updateClubMembershipFormBtn').html('Submitting...');
                $('#clubMembershipForm').submit();
            });

            document.getElementById("telephone_no").oninput = function() {
                let inputValue = this.value;
                let errorMsg = document.getElementById("telephone_noError");

                if (!/^\d*$/.test(inputValue)) {
                    errorMsg.innerText = "Only numeric values are allowed!";
                    this.value = inputValue.replace(/\D/g, ''); // Remove non-numeric characters
                } else {
                    errorMsg.innerText = "";
                }
            };

            document.getElementById("mobile").oninput = function() {
                let inputValue = this.value;
                let errorMsg = document.getElementById("mobileError");

                if (!/^\d*$/.test(inputValue)) {
                    errorMsg.innerText = "Only numeric values are allowed!";
                    this.value = inputValue.replace(/\D/g, ''); // Remove non-numeric characters
                } else {
                    errorMsg.innerText = "";
                }
            };

            document.getElementById("year_of_allotment").oninput = function() {
                let inputValue = this.value;
                let errorMsg = document.getElementById("year_of_allotmentError");

                if (!/^\d*$/.test(inputValue)) {
                    errorMsg.innerText = "Only numeric values are allowed!";
                    this.value = inputValue.replace(/\D/g, ''); // Remove non-numeric characters
                } else {
                    errorMsg.innerText = "";
                }
            };

            document.getElementById("email").oninput = function() {
                let inputValue = this.value;
                let errorMsg = document.getElementById("emailError");
                let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                if (!regex.test(inputValue)) {
                    errorMsg.innerText = "Please enter a valid email address!";
                } else {
                    errorMsg.innerText = "";
                }
            };

        });
    </script>
@endsection
