<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Models\ClubMembership;
use App\Models\ClubMembershipApplicationsHistory;
use App\Models\ClubMembershipDgc;
use App\Models\ClubMembershipIhc;
use App\Models\DgcsApplicationsHistory;
use App\Models\IhcsApplicationsHistory;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ClubMembershipBackendController extends Controller
{
    public function index(Request $request)
    {
        $statusArr = ['CM_NEW', 'CM_PEN', 'CM_INP', 'CM_REJ', 'CM_APP'];
        $items = Item::where('group_id', 17012)->whereIn('item_code', $statusArr)->get();

        $getStatusId = '';
        if ($request->query('status')) {
            $getStatusId = Item::where('item_code', Crypt::decrypt($request->query('status')))->value('id');
        }

        return view('club_membership.index', compact('items', 'getStatusId'));
    }

    public function getClubMembershipList(Request $request)
    {
        $user = Auth::user();
        $query = ClubMembership::query()
            ->leftJoin('dgcs_applications as cmd', 'club_membership_applications.id', '=', 'cmd.membership_app_id')
            ->leftJoin('ihcs_applications as cmi', 'club_membership_applications.id', '=', 'cmi.membership_app_id')
            ->leftJoin('items', 'club_membership_applications.status', '=', 'items.id')
            ->select(
                'club_membership_applications.*',
                'cmd.is_post_under_central_staffing_scheme',
                'cmd.handicap_certification',
                'cmd.ihc_nomination_date',
                'cmi.individual_membership_date_and_remark',
                'items.item_code',
                'items.item_name as status_name' // Renaming to avoid conflicts
            );

        // Apply status filter if provided
        if (!empty($request->status)) {
            $query->where('club_membership_applications.status', $request->status);
        }

        $columns = ['id', 'name', 'category', 'club_type', 'designation', 'name_of_service', 'year_of_allotment', 'date_of_application', 'date_of_joining_central_deputation', 'pay_scale', 'other_relevant_information', 'status_name', 'created_at'];
        $totalData = $query->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumnIndex = $request->input('order.0.column');
        $dir = $request->input('order.0.dir', 'desc');

        $order = $columns[$orderColumnIndex] ?? 'created_at';

        // Searching
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('club_membership_applications.name', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.category', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.club_type', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.designation', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.name_of_service', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.year_of_allotment', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.date_of_application', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.date_of_joining_central_deputation', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.pay_scale', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.other_relevant_information', 'LIKE', "%{$search}%")
                    ->orWhere('items.item_name', 'LIKE', "%{$search}%")
                    ->orWhere('club_membership_applications.created_at', 'LIKE', "%{$search}%");
            });

            $totalFiltered = $query->count();
        }

        $getClubMembershipData = $query->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->get();

        $statusClasses = [
            'CM_REJ' => 'badge-cancelled',
            'CM_NEW' => 'badge-new',
            'CM_PEN' => 'badge-pending',
            'CM_INP' => 'badge-inprocess',
            'CM_APP' => 'badge-resolved'
        ];
        // $statusClasses = [
        //     'CM_REJ' => 'highlight_value statusRejected',
        //     'CM_NEW' => 'highlight_value statusNew',
        //     'CM_INP' => 'highlight_value statusSecondary',
        //     'CM_PEN' => 'highlight_value bg-light-info',
        //     'CM_APP' => 'highlight_value landtypeFreeH',
        // ];

        $counter = 1;
        $data = [];
        foreach ($getClubMembershipData as $clubMembership) {
            $actionBtnHtml = $itemCode =  '';
            $nestedData['id'] = $counter++;
            $nestedData['name'] = $clubMembership->name;
            $nestedData['category'] = $clubMembership->category;
            $nestedData['club_type'] = $clubMembership->club_type;
            $nestedData['designation'] = $clubMembership->designation;
            $nestedData['name_of_service'] = $clubMembership->name_of_service;
            $nestedData['year_of_allotment'] = $clubMembership->year_of_allotment;
            $nestedData['date_of_application'] = !empty($clubMembership->date_of_application) ? Carbon::parse($clubMembership->date_of_application)->format('d/m/Y') : '';
            $nestedData['date_of_joining_central_deputation'] = !empty($clubMembership->date_of_joining_central_deputation) ? Carbon::parse($clubMembership->date_of_joining_central_deputation)->format('d/m/Y') : '';
            $nestedData['pay_scale'] = $clubMembership->pay_scale;
            $nestedData['other_relevant_information'] = $clubMembership->other_relevant_information;
            $class = $statusClasses[$clubMembership->item_code] ?? 'text-secondary bg-light';
            // $nestedData['status'] = '<span class="' . $class . '">' . ucwords($clubMembership->status_name) . '</span>';
            $nestedData['status'] = '<span class="theme-badge ' . $class . '">' . ucwords($clubMembership->status_name) . '</span>';
            $nestedData['created_at'] = $clubMembership->created_at->format('d/m/Y H:i:s');
            // Check if user has view permission
            if ($user->hasPermissionTo('club.membership.view')) {
                $actionBtnHtml .= '<a href="' . url('club-membership/details/' . $clubMembership->id) . '"><button type="button" class="btn btn-primary px-3 mr-2">View</button></a>';
            }
            if ($user->hasPermissionTo('club.membership.update')) {
                $itemCode = getServiceCodeById($clubMembership->status);
                if ($itemCode != 'CM_APP' && $itemCode != 'CM_REJ') {
                    $actionBtnHtml .= '<a href="' . url('club-membership/edit/' . $clubMembership->id) . '"><button type="button" class="btn btn-secondary px-3">Edit</button></a>';
                }
            }
            $nestedData['action'] = $actionBtnHtml;
            $data[] = $nestedData;
        }

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ]);
    }


    public function getClubMembershipDetails($clubMembershipId)
    {
        
        try {
            $user = Auth::user();
            if (empty($clubMembershipId)) {
                return redirect()->back()->with('error', 'Club membership id does not exists.');
            }
            $getClubMembershipDetails = ClubMembership::with('dgcDetails', 'ihcDetails')->where('id', $clubMembershipId)->first();
            if (empty($getClubMembershipDetails)) {
                return redirect()->back()->with('error', 'Club membership details not found.');
            }

            $isActionButtonVisible = false;

            // Get all section IDs for the user
            $sectionIdArr = DB::table('section_user')->where('user_id', Auth::id())->pluck('section_id')->toArray();

            // Get the section ID for 'LS2B'
            $sectionId = DB::table('sections')->where('section_code', 'LS2B')->value('id');

            // Check if the section ID exists in the user's sections
            if ($sectionId && in_array($sectionId, $sectionIdArr) && $user->hasPermissionTo('club.membership.action')) {
                $isActionButtonVisible = true;
            }

            return view('club_membership.details', compact('getClubMembershipDetails','isActionButtonVisible'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        return view('club_membership.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'date_of_application' => 'required|date',
            'name' => 'required|string',
            'category' => 'required|string',
            'designation' => 'required|string',
            'designation_equivalent_to' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|string',
            'name_of_service' => 'required|string',
            'year_of_allotment' => 'required|integer',
            'date_of_joining_central_deputation' => 'required|date',
            'expected_date_of_tenure_completion' => 'required|date',
            'date_of_superannuation' => 'required|date',
            'office_address' => 'required|string',
            'club_type' => 'required|in:DGC,IHC',
            'document' => 'required|mimes:pdf|max:5120', // 5120 KB = 5 MB
        ];

        // Conditional Validation Based on Club Type
        if ($request->club_type === 'IHC') {
            $rules += [
                'pay_scale_ihc' => 'required|string',
                'individual_membership_date_and_remark' => 'required|string',
                'dgc_tenure_start_date_ihc' => 'required|date',
                'dgc_tenure_end_date_ihc' => 'required|date',
                'consentIhc' => 'required', // Consider changing this to 'accepted' if it's a checkbox
            ];
        } else {
            $rules += [
                'pay_scale_dgc' => 'required|string',
                'is_post_under_central_staffing_scheme' => 'required|string',
                'regular_membership_date_and_remark' => 'required|string',
                'dgc_tenure_start_date_dgc' => 'required|date',
                'dgc_tenure_end_date_dgc' => 'required|date',
                'handicap_certification' => 'required|string',
                'ihc_nomination_date' => 'required|date',
                'consentDgc' => 'required', // Consider changing this to 'accepted' if it's a checkbox
            ];
        }

        // Conditional Validation Based on Category
        if ($request->filled('category') && $request->category === 'other') {
            $rules += [
                'other_category' => 'required|string',
            ];
        }
        // Conditional Validation Based on Equivalent Designation
        if ($request->filled('designation_equivalent_to') && $request->designation_equivalent_to === 'OTHER') {
            $rules += [
                'other_designation_equivalent_to' => 'required|string',
            ];
        }

        $request->validate($rules);

        if ($request->club_type === 'IHC') {
            $payScale = $request->pay_scale_ihc;
            $consent = $request->consentIhc;
        } elseif ($request->club_type === 'DGC') {
            $payScale = $request->pay_scale_dgc;
            $consent = $request->consentDgc;
        }
        // dd($request->all());

        $document  = $request->document;
        if (isset($document) && $document != '') {
            $docName = preg_replace('/\s+/', '_', $request->name);
            $docName .= "_" . $request->club_type;
            $document = GeneralFunctions::uploadFile($document, '/club_membership', $docName);
        }

        try {
            DB::beginTransaction();
            
            $membership = ClubMembership::create([
                'category' => $request->category,
                'other_category' => $request->other_category,
                'date_of_application' => $request->date_of_application,
                'name' => $request->name,
                'designation' => $request->designation,
                'designation_equivalent_to' => $request->designation_equivalent_to,
                'other_designation_equivalent_to' => $request->other_designation_equivalent_to,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'name_of_service' => $request->name_of_service,
                'year_of_allotment' => $request->year_of_allotment,
                'club_type' => $request->club_type,
                'date_of_joining_central_deputation' => $request->date_of_joining_central_deputation,
                'expected_date_of_tenure_completion' => $request->expected_date_of_tenure_completion,
                'date_of_superannuation' => $request->date_of_superannuation,
                'office_address' => $request->office_address,
                'telephone_no' => $request->telephone_no,
                'pay_scale' => $payScale,
                'present_previous_membership_of_other_clubs' => $request->present_previous_membership_of_other_clubs,
                'other_relevant_information' => $request->other_relevant_information,
                'status' => getServiceType('CM_NEW'),
                'consent' => $consent ? true : false,
            ]);

            if ($request->club_type === 'IHC') {
                ClubMembershipIhc::create([
                    'membership_app_id' => $membership->id,
                    'individual_membership_date_and_remark' => $request->individual_membership_date_and_remark,
                    'dgc_tenure_start_date' => $request->dgc_tenure_start_date_ihc,
                    'dgc_tenure_end_date' => $request->dgc_tenure_end_date_ihc,
                    'ihcs_doc' => $document,
                ]);
            } elseif ($request->club_type === 'DGC') {
                ClubMembershipDgc::create([
                    'membership_app_id' => $membership->id,
                    'is_post_under_central_staffing_scheme' => $request->is_post_under_central_staffing_scheme,
                    'regular_membership_date_and_remark' => $request->regular_membership_date_and_remark,
                    'dgc_tenure_start_date' => $request->dgc_tenure_start_date_dgc,
                    'dgc_tenure_end_date' => $request->dgc_tenure_end_date_dgc,
                    'handicap_certification' => $request->handicap_certification,
                    'ihc_nomination_date' => $request->ihc_nomination_date,
                    'dgcs_doc' => $document,
                ]);
            }
            DB::commit();
            return redirect('club-membership')->with('success', 'Club Membership application submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Club membership creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        $club = ClubMembership::find($request->id);
        if ($club) {
            $club->status = $request->status;
            $club->remark = $request->remark ?? null;
            $club->save();
            return response()->json(['status' => true, 'message' => 'Club membership status successfully updated.', 'redirect_url' => route('club.membership.index')]);
        }
        return response()->json(['status' => false, 'message' => 'Something went wrong.']);
    }

    public function allotmentClubMembership(Request $request)
    {
        $club = ClubMembership::find($request->id);

        if (!$club) {
            return response()->json(['status' => false, 'message' => 'Club Membership Details Not found for Provided Id.']);
        }

        $clubTypes = [
            'IHC' => ['DGC', 'Delhi Golf Club'],
            'DGC' => ['IHC', 'India Habitat Centre']
        ];

        [$existClubType, $clubTypeDisplayName] = $clubTypes[$club->club_type] ?? [null, null];

        $statusArr = [
            getServiceType('CM_APP'),
            getServiceType('CM_PEN')
            // getServiceType('CM_INP')
        ];

        if (
            $existClubType &&
            ClubMembership::where('club_type', $existClubType)
                ->where('email', $club->email)
                ->whereIn('status', $statusArr)
                ->exists()
        ) {
            return response()->json([
                'status' => false,
                'message' => "This member already has a {$clubTypeDisplayName} membership."
            ]);
        }

        $club->update([
            'membership_id' => $request->membershipId ?? null,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Club membership successfully allotted.',
            'redirect_url' => route('club.membership.index')
        ]);
    }

    public function editClubMembershipDetails($clubMembershipId)
    {
        try {
            if (empty($clubMembershipId)) {
                return redirect()->back()->with('error', 'Club membership id does not exists.');
            }
            $getClubMembershipDetails = ClubMembership::with('dgcDetails', 'ihcDetails')->where('id', $clubMembershipId)->first();
            if (empty($getClubMembershipDetails)) {
                return redirect()->back()->with('error', 'Club membership details not found.');
            }
            return view('club_membership.edit', compact('getClubMembershipDetails'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'date_of_application' => 'required|date',
            'name' => 'required|string',
            'category' => 'required|string',
            'designation' => 'required|string',
            'designation_equivalent_to' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|string',
            'name_of_service' => 'required|string',
            'year_of_allotment' => 'required|integer',
            'date_of_joining_central_deputation' => 'required|date',
            'expected_date_of_tenure_completion' => 'required|date',
            'date_of_superannuation' => 'required|date',
            'office_address' => 'required|string',
            'club_type' => 'required|in:DGC,IHC',
            // 'document' => 'required|mimes:pdf|max:5120', // 5120 KB = 5 MB
        ];

        // Conditional Validation Based on Club Type
        if ($request->club_type === 'IHC') {
            $rules += [
                'pay_scale_ihc' => 'required|string',
                'individual_membership_date_and_remark' => 'required|string',
                'dgc_tenure_start_date_ihc' => 'required|date',
                'dgc_tenure_end_date_ihc' => 'required|date',
            ];
        } else {
            $rules += [
                'pay_scale_dgc' => 'required|string',
                'is_post_under_central_staffing_scheme' => 'required|string',
                'regular_membership_date_and_remark' => 'required|string',
                'dgc_tenure_start_date_dgc' => 'required|date',
                'dgc_tenure_end_date_dgc' => 'required|date',
                'handicap_certification' => 'required|string',
                'ihc_nomination_date' => 'required|date',
            ];
        }

        // Conditional Validation Based on Category
        if ($request->filled('category') && $request->category === 'other') {
            $rules += [
                'other_category' => 'required|string',
            ];
        }
        // Conditional Validation Based on Equivalent Designation
        if ($request->filled('designation_equivalent_to') && $request->designation_equivalent_to === 'OTHER') {
            $rules += [
                'other_designation_equivalent_to' => 'required|string',
            ];
        }

        $request->validate($rules);

        if ($request->club_type === 'IHC') {
            $payScale = $request->pay_scale_ihc;
        } elseif ($request->club_type === 'DGC') {
            $payScale = $request->pay_scale_dgc;
        }

        // Upload document if provided
        $document  = $request->document;
        if (isset($document) && $document != '') {
            $docName = preg_replace('/\s+/', '_', $request->name);
            $docName .= "_" . $request->club_type;
            $document = GeneralFunctions::uploadFile($document, '/club_membership', $docName);
        }

        try {
            DB::beginTransaction();
            if(!empty($request->clubMembershipId)){
                $membership = ClubMembership::find($request->clubMembershipId); // Fetch existing membership record
                if($membership){
                    $oldMembershipDetails = $membership->getOriginal();
                    $membership->category = isset($request->category) ? $request->category : $membership->category;
                    $membership->other_category = isset($request->other_category) ? $request->other_category : $membership->other_category;
                    $membership->date_of_application = isset($request->date_of_application) ? $request->date_of_application : $membership->date_of_application;
                    $membership->name = isset($request->name) ? $request->name : $membership->name;
                    $membership->designation = isset($request->designation) ? $request->designation : $membership->designation;
                    $membership->designation_equivalent_to = isset($request->designation_equivalent_to) ? $request->designation_equivalent_to : $membership->designation_equivalent_to;
                    $membership->other_designation_equivalent_to = isset($request->other_designation_equivalent_to) ? $request->other_designation_equivalent_to : $membership->other_designation_equivalent_to;
                    $membership->mobile = isset($request->mobile) ? $request->mobile : $membership->mobile;
                    $membership->email = isset($request->email) ? $request->email : $membership->email;
                    $membership->name_of_service = isset($request->name_of_service) ? $request->name_of_service : $membership->name_of_service;
                    $membership->year_of_allotment = isset($request->year_of_allotment) ? $request->year_of_allotment : $membership->year_of_allotment;
                    $membership->club_type = isset($request->club_type) ? $request->club_type : $membership->club_type;
                    $membership->date_of_joining_central_deputation = isset($request->date_of_joining_central_deputation) ? $request->date_of_joining_central_deputation : $membership->date_of_joining_central_deputation;
                    $membership->expected_date_of_tenure_completion = isset($request->expected_date_of_tenure_completion) ? $request->expected_date_of_tenure_completion : $membership->expected_date_of_tenure_completion;
                    $membership->date_of_superannuation = isset($request->date_of_superannuation) ? $request->date_of_superannuation : $membership->date_of_superannuation;
                    $membership->office_address = isset($request->office_address) ? $request->office_address : $membership->office_address;
                    $membership->telephone_no = isset($request->telephone_no) ? $request->telephone_no : $membership->telephone_no;
                    $membership->pay_scale = isset($payScale) ? $payScale : $membership->pay_scale;
                    $membership->present_previous_membership_of_other_clubs = isset($request->present_previous_membership_of_other_clubs) ? $request->present_previous_membership_of_other_clubs : $membership->present_previous_membership_of_other_clubs;
                    $membership->other_relevant_information = isset($request->other_relevant_information) ? $request->other_relevant_information : $membership->other_relevant_information;
                    if ($membership->isDirty()) {
                        $membership->save();
                        $membershipChanges = $membership->getChanges();
                        $membershipHistory = new ClubMembershipApplicationsHistory();
                        $membershipHistory->membership_app_id = $request->clubMembershipId;
                        foreach ($membershipChanges as $key => $change) {
                            if ($key != 'updated_at') {
                                $membershipHistory->$key = $oldMembershipDetails[$key];
                                $newKey = 'new_' . $key;
                                $membershipHistory->$newKey = $change;
                            }
                        }
                        $membershipHistory->updated_by = Auth::id();
                        $membershipHistory->save();
                    }

                    if ($request->club_type === 'IHC') {
                        $ihcMembership = ClubMembershipIhc::where('membership_app_id', $membership->id)->first();
                        if ($ihcMembership) {
                            $oldIhcMembershipDetails = $ihcMembership->getOriginal();
                            $ihc_app_id = $ihcMembership->id;
                            // Update existing record
                            $ihcMembership->individual_membership_date_and_remark = $request->individual_membership_date_and_remark ?? $ihcMembership->individual_membership_date_and_remark;
                            $ihcMembership->dgc_tenure_start_date = $request->dgc_tenure_start_date_ihc ?? $ihcMembership->dgc_tenure_start_date;
                            $ihcMembership->dgc_tenure_end_date = $request->dgc_tenure_end_date_ihc ?? $ihcMembership->dgc_tenure_end_date;
                            $ihcMembership->ihcs_doc = $document ?? $ihcMembership->ihcs_doc;
                            if ($ihcMembership->isDirty()) {
                                $ihcMembership->save();
                                $changesIhcMembershipChange = $ihcMembership->getChanges();
                                $ihcMembershipHistory = new IhcsApplicationsHistory();
                                $ihcMembershipHistory->membership_app_id = $request->clubMembershipId;
                                $ihcMembershipHistory->ihc_app_id = $ihc_app_id;
                                foreach ($changesIhcMembershipChange as $key => $change) {
                                    if ($key != 'updated_at') {
                                        $ihcMembershipHistory->$key = $oldIhcMembershipDetails[$key];
                                        $newKey = 'new_' . $key;
                                        $ihcMembershipHistory->$newKey = $change;
                                    }
                                }
                                $ihcMembershipHistory->updated_by = Auth::id();
                                $ihcMembershipHistory->save();
                            }
                        } 
                    } 
                    
                    if ($request->club_type === 'DGC') {
                        $dgcMembership = ClubMembershipDgc::where('membership_app_id', $membership->id)->first();
                        if ($dgcMembership) {
                            $oldDgcMembershipDetails = $dgcMembership->getOriginal();
                            $dgc_app_id = $dgcMembership->id;
                            // Update existing record
                            $dgcMembership->is_post_under_central_staffing_scheme = $request->is_post_under_central_staffing_scheme ?? $dgcMembership->is_post_under_central_staffing_scheme;
                            $dgcMembership->regular_membership_date_and_remark = $request->regular_membership_date_and_remark ?? $dgcMembership->regular_membership_date_and_remark;
                            $dgcMembership->dgc_tenure_start_date = $request->dgc_tenure_start_date_dgc ?? $dgcMembership->dgc_tenure_start_date;
                            $dgcMembership->dgc_tenure_end_date = $request->dgc_tenure_end_date_dgc ?? $dgcMembership->dgc_tenure_end_date;
                            $dgcMembership->handicap_certification = $request->handicap_certification ?? $dgcMembership->handicap_certification;
                            $dgcMembership->ihc_nomination_date = $request->ihc_nomination_date ?? $dgcMembership->ihc_nomination_date;
                            $dgcMembership->dgcs_doc = $document ?? $dgcMembership->dgcs_doc;
                            if ($dgcMembership->isDirty()) {
                                $dgcMembership->save();
                                $changesDgcMembershipChange = $dgcMembership->getChanges();
                                $dgcMembershipHistory = new DgcsApplicationsHistory();
                                $dgcMembershipHistory->membership_app_id = $request->clubMembershipId;
                                $dgcMembershipHistory->dgc_app_id = $dgc_app_id;
                                foreach ($changesDgcMembershipChange as $key => $change) {
                                    if ($key != 'updated_at') {
                                        $dgcMembershipHistory->$key = $oldDgcMembershipDetails[$key];
                                        $newKey = 'new_' . $key;
                                        $dgcMembershipHistory->$newKey = $change;
                                    }
                                }
                                $dgcMembershipHistory->updated_by = Auth::id();
                                $dgcMembershipHistory->save();
                            }
                        } 
                    }

                }
            }
            DB::commit();
            return redirect('club-membership')->with('success', 'Club Membership application updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Club membership creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /*public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'date_of_application' => 'required|date',
            'name' => 'required|string',
            'category' => 'required|string',
            'designation' => 'required|string',
            'designation_equivalent_to' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|string',
            'name_of_service' => 'required|string',
            'year_of_allotment' => 'required|integer',
            'date_of_joining_central_deputation' => 'required|date',
            'expected_date_of_tenure_completion' => 'required|date',
            'date_of_superannuation' => 'required|date',
            'office_address' => 'required|string',
            'club_type' => 'required|in:DGC,IHC',
            // 'document' => 'required|mimes:pdf|max:5120', // 5120 KB = 5 MB
        ];

        // Conditional Validation Based on Club Type
        if ($request->club_type === 'IHC') {
            $rules += [
                'pay_scale_ihc' => 'required|string',
                'individual_membership_date_and_remark' => 'required|string',
                'dgc_tenure_start_date_ihc' => 'required|date',
                'dgc_tenure_end_date_ihc' => 'required|date',
            ];
        } else {
            $rules += [
                'pay_scale_dgc' => 'required|string',
                'is_post_under_central_staffing_scheme' => 'required|string',
                'regular_membership_date_and_remark' => 'required|string',
                'dgc_tenure_start_date_dgc' => 'required|date',
                'dgc_tenure_end_date_dgc' => 'required|date',
                'handicap_certification' => 'required|string',
                'ihc_nomination_date' => 'required|date',
            ];
        }

        // Conditional Validation Based on Category
        if ($request->filled('category') && $request->category === 'other') {
            $rules += [
                'other_category' => 'required|string',
            ];
        }
        // Conditional Validation Based on Equivalent Designation
        if ($request->filled('designation_equivalent_to') && $request->designation_equivalent_to === 'OTHER') {
            $rules += [
                'other_designation_equivalent_to' => 'required|string',
            ];
        }

        $request->validate($rules);

        if ($request->club_type === 'IHC') {
            $payScale = $request->pay_scale_ihc;
        } elseif ($request->club_type === 'DGC') {
            $payScale = $request->pay_scale_dgc;
        }
        
        try {
            DB::beginTransaction();

            if (!empty($request->clubMembershipId)) {
                $membership = ClubMembership::find($request->clubMembershipId);
                if ($membership) {
                    $oldMembershipDetails = $membership->getOriginal();

                    // Helper function to update fields
                    $fieldsToUpdate = [
                        'category',
                        'other_category',
                        'date_of_application',
                        'name',
                        'designation',
                        'designation_equivalent_to',
                        'other_designation_equivalent_to',
                        'mobile',
                        'email',
                        'name_of_service',
                        'year_of_allotment',
                        'club_type',
                        'date_of_joining_central_deputation',
                        'expected_date_of_tenure_completion',
                        'date_of_superannuation',
                        'office_address',
                        'telephone_no',
                        'present_previous_membership_of_other_clubs',
                        'other_relevant_information'
                    ];

                    foreach ($fieldsToUpdate as $field) {
                        $membership->$field = $request->$field ?? $membership->$field;
                    }
                    $membership->pay_scale = $payScale ?? $membership->pay_scale;
                    if ($membership->isDirty()) {
                        $membership->save();
                        // Save Membership History
                        $this->saveMembershipHistory($membership, $oldMembershipDetails);
                    }

                    // Handle specific club types (IHC and DGC)
                    if ($membership->club_type === 'IHC') {

                        $ihcMembership = ClubMembershipIhc::where('membership_app_id', $membership->id)->first();
                        if ($ihcMembership) {
                            $oldIhcMembershipDetails = $ihcMembership->getOriginal();
                            // Helper function to update fields
                            $fieldsToUpdateCMIhc = [
                                'individual_membership_date_and_remark',
                                'dgc_tenure_start_date',
                                'dgc_tenure_end_date',
                                'ihcs_doc'
                            ];

                            foreach ($fieldsToUpdateCMIhc as $field) {
                                $ihcMembership->$field = $request->$field ?? $ihcMembership->$field;
                            }
                            // Upload document if provided
                            if ($request->hasFile('document')) {
                                $docName = preg_replace('/\s+/', '_', $request->name) . "_" . $request->club_type;
                                $ihcMembership->ihcs_doc = GeneralFunctions::uploadFile($request->file('document'), '/club_membership', $docName);
                            }
                            if ($ihcMembership->isDirty()) {
                                $ihcMembership->save();
                                $this->saveIhcMembershipHistory($ihcMembership, $oldIhcMembershipDetails);
                            }
                        }
                    } elseif ($membership->club_type === 'DGC') {
                        $dgcMembership = ClubMembershipDgc::where('membership_app_id', $membership->id)->first();
                        if ($dgcMembership) {
                            $oldDgcMembershipDetails = $dgcMembership->getOriginal();
                            // Helper function to update fields
                            $fieldsToUpdateCMDGC = [
                                'is_post_under_central_staffing_scheme',
                                'regular_membership_date_and_remark',
                                'dgc_tenure_start_date',
                                'dgc_tenure_end_date',
                                'handicap_certification',
                                'ihc_nomination_date',
                                'dgcs_doc'
                            ];
                            foreach ($fieldsToUpdateCMDGC as $field) {
                                $dgcMembership->$field = $request->$field ?? $dgcMembership->$field;
                            }
                            // Upload document if provided
                            if ($request->hasFile('document')) {
                                $docName = preg_replace('/\s+/', '_', $request->name) . "_" . $request->club_type;
                                $dgcMembership->dgcs_doc = GeneralFunctions::uploadFile($request->file('document'), '/club_membership', $docName);
                            }
                            if ($dgcMembership->isDirty()) {
                                $dgcMembership->save();
                                $this->saveDgcMembershipHistory($dgcMembership, $oldDgcMembershipDetails);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Club Membership application updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Club membership creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function saveMembershipHistory($membership, $oldMembershipDetails)
    {
        $membershipChanges = $membership->getChanges();
        $membershipHistory = new ClubMembershipApplicationsHistory();
        $membershipHistory->membership_app_id = $membership->id;

        foreach ($membershipChanges as $key => $change) {
            if ($key != 'updated_at') {
                $membershipHistory->$key = $oldMembershipDetails[$key];
                $membershipHistory->{'new_' . $key} = $change;
            }
        }

        $membershipHistory->updated_by = Auth::id();
        $membershipHistory->save();
    }


    private function saveIhcMembershipHistory($ihcMembership, $oldIhcMembershipDetails)
    {
        $changesIhcMembershipChange = $ihcMembership->getChanges();
        $ihcMembershipHistory = new IhcsApplicationsHistory();
        $ihcMembershipHistory->membership_app_id = $ihcMembership->membership_app_id;
        $ihcMembershipHistory->ihc_app_id = $ihcMembership->id;

        foreach ($changesIhcMembershipChange as $key => $change) {
            if ($key != 'updated_at') {
                $ihcMembershipHistory->$key = $oldIhcMembershipDetails[$key];
                $ihcMembershipHistory->{'new_' . $key} = $change;
            }
        }

        $ihcMembershipHistory->updated_by = Auth::id();
        $ihcMembershipHistory->save();
    }

    private function saveDgcMembershipHistory($dgcMembership, $oldDgcMembershipDetails)
    {
        $changesDgcMembershipChange = $dgcMembership->getChanges();
        $dgcMembershipHistory = new DgcsApplicationsHistory();
        $dgcMembershipHistory->membership_app_id = $dgcMembership->membership_app_id;
        $dgcMembershipHistory->dgc_app_id = $dgcMembership->id;

        foreach ($changesDgcMembershipChange as $key => $change) {
            if ($key != 'updated_at') {
                $dgcMembershipHistory->$key = $oldDgcMembershipDetails[$key];
                $dgcMembershipHistory->{'new_' . $key} = $change;
            }
        }

        $dgcMembershipHistory->updated_by = Auth::id();
        $dgcMembershipHistory->save();
    }*/
}
