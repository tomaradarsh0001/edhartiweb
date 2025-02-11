<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralFunctions;
use App\Http\Controllers\Controller;
use App\Models\ApplicantUserDetail;
use App\Models\Application;
use App\Models\ApplicationAppointmentLink;
use App\Models\Holiday;
use App\Models\NewlyAddedProperty;
use App\Models\OldColony;
use App\Models\PropertyMaster;
use App\Models\PropertySectionMapping;
use App\Models\Section;
use App\Models\User;
use App\Models\UserProperty;
use App\Services\ColonyService;
use App\Services\MisService;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        return view('applicant.index', ['user' => $user]);
    }

    public function propertiesDetails(ColonyService $colonyService, MisService $misService)
    {
        $user = User::with('applicantUserDetails', 'userProperties.documents', 'userProperties.flat')->findOrFail(Auth::id());
        $colonyList = $colonyService->getColonyList();
        $propertyTypes = $misService->getItemsByGroupId(1052);
        $newProperties = NewlyAddedProperty::with('flat')->where('user_id', Auth::id())->where('status', getStatusName('RS_PEN'))->get();
        return view('applicant.property', compact('user', 'colonyList', 'propertyTypes', 'newProperties'));
    }


    public function applicationHistory()
    {
        return view('applicant.application_history');
    }

    public function storeNewProperty(Request $request)
    {
        $userId = Auth::id();
        if (isset($request->propertyId)) {
            $locality   = null;
            $locality_name   = strtolower($request->localityInvFill);
            $block      = $request->blocknoInvFill;
            $plot       = $request->plotnoInvFill;
            $knownAs    = $request->knownasInvFill;
            $landUseType    = $request->landUseInvFill;
            $landUseSubType    = $request->landUseSubtypeInvFill;
        } else {
            $locality   = $request->localityInv;
            $locality_name   = null;
            $block      = $request->blockInv;
            $plot       = $request->plotInv;
            $knownAs    = $request->knownasInv;
            $landUseType    = $request->landUseInv;
            $landUseSubType    = $request->landUseSubtypeInv;
        }
        
        if(isset($request->isPropertyFlat)){
            $is_property_flat = $request->isPropertyFlat;
            if(isset($request->propertyId)){
                $flat_no = $request->flat_no_rec_not_found_org;
            } else {
                if(isset($request->isFlatNotInList)){
                    $flat_no = $request->flat_no;
                } else {
                    if(!empty($request->flat)){
                        $flat_id = $request->flat;
                        $flat_no = $request->flat_no;
                    } else {
                        $flat_no = $request->flat_no_rec_not_found;
                    }
                }
            }
        }

        //Convert Flat No is lower case
        if(!empty($flat_no)){
            $flat_no = strtolower($flat_no);
        }

        $saleDeedDoc  = $request->saleDeedDocInv;
        $builAgreeDoc = $request->BuilAgreeDocInv;
        $leaseDeedDoc = $request->leaseDeedDocInv;
        $subMutLtrDoc = $request->subMutLtrDocInv;
        $otherDocDoc = $request->otherDocInv;
        $ownerLessDocInv = $request->ownerLessDocInv;

        if (!is_null($locality)) {
           //Check existing property for applicant
            $userPropertyQuery = UserProperty::where([
                ['user_id', '=', $userId],
                ['locality', '=', $locality],
                ['block', '=', $block],
                ['plot', '=', $plot],
            ]);
            if (!empty($flat_id)) {
                $userPropertyQuery->where('flat_id', '=', $flat_id);
            }
            $userPropertyExist = $userPropertyQuery->first();
            if ($userPropertyExist) {
                if (!empty($flat_no)) {
                    return redirect()->back()->with('failure', 'Property already exists with Property ID ' . $userPropertyExist->new_property_id . ' & Flat ID ' . $request->flat_no);
                } else {
                    return redirect()->back()->with('failure', 'Property already exists with Property ID ' . $userPropertyExist->new_property_id);
                }
            }

            //Check property alredy exist for this user or not
            $newPropertyQuery = NewlyAddedProperty::where([
                ['user_id', '=', $userId],
                ['locality', '=', $locality],
                ['block', '=', $block],
                ['plot', '=', $plot],
            ]);
            if(!empty($flat_id) && !empty($flat_no)){
                $newPropertyQuery->where('flat_id', '=', $flat_id)->where('flat_no', '=', $flat_no);
            } else if (!empty($flat_id)){
                $newPropertyQuery->where('flat_id', '=', $flat_id);
            } else if (!empty($flat_no)){
                $newPropertyQuery->where('flat_no', '=', $flat_no);
            }
            $newPropertyExist = $newPropertyQuery->first();
             if ($newPropertyExist) {
                if (isset($request->flat_no)) {
                    return redirect()->back()->with('failure', 'Property record already exist for given property id ' . $newPropertyExist->old_property_id . ' & Flat ID ' . $request->flat_no);
                } else {
                    return redirect()->back()->with('failure', 'Property record already exist for given property id ' . $newPropertyExist->old_property_id);
                }
            }

            //Fetch Suggested Property Id from Property Master
            $property = PropertyMaster::where('new_colony_name', $locality)->where('block_no', $block)->where('plot_or_property_no', $plot)->first();
            if (!empty($property['id'])) {
                $oldPropertyId = $property['old_propert_id'];
                $suggestedPropertyId = $property['id'];
            } else {
                $oldPropertyId = null;
                $suggestedPropertyId = null;
            }

        } else {
            if(!is_null($locality_name)){
                //Check if Property related to flat
                if(isset($request->isPropertyFlat))
                {
                    $is_property_flat = $request->isPropertyFlat;
                }
                //Check property alredy exist for this user or not
                $newPropertyQuery = NewlyAddedProperty::where([
                    ['user_id', '=', $userId],
                    ['locality_name', '=', $locality_name],
                    ['block', '=', $block],
                    ['plot', '=', $plot],
                ]);
                if (isset($request->propertyId) && isset($request->isPropertyFlat)) {
                    $newPropertyQuery->where('flat_no', '=', $request->flat_no_rec_not_found_org);
                }
                $newPropertyExist = $newPropertyQuery->first();
                if ($newPropertyExist) {
                    if (isset($request->flat_no)) {
                        return redirect()->back()->with('failure', 'Property record already exist for given property id ' . $newPropertyExist->old_property_id . ' & Flat ID ' . $request->flat_no);
                    } else {
                        return redirect()->back()->with('failure', 'Property record already exist for given property id ' . $newPropertyExist->old_property_id);
                    }
                }
            }
        }

        $getApplicantNumber = ApplicantUserDetail::where('user_id', $userId)->first();
        if(empty($getApplicantNumber['id'])){
            return redirect()->back()->with('failure', 'Applicant registration number not found in user registration');
        }
        
        $applicantNumber = $getApplicantNumber['applicant_number'];
        
         // Check if locality enter manually, it means registration goes to IT Cell section - Lalit (20/Jan/2025)
         if (is_null($locality)) {
            //ITC stand for IT Cell Section table id - Lalit (20/Jan/2025)
            $section = Section::where('section_code', 'ITC')->pluck('id')->first();
        } else {
            $section = PropertySectionMapping::where('colony_id', $locality)
                ->where('property_type', $landUseType)
                ->where('property_subtype', $landUseSubType)
                ->pluck('section_id')->first();
        }
        if(empty($section)){
            return redirect()->back()->with('failure', 'Section not found for added property');
        }

        if (is_null($locality)) {
            $colonyCode = $locality_name;
        } else {
            $colony = OldColony::find($locality);
            $colonyCode = $colony->code;
        }

        if (isset($saleDeedDoc)) {
            $saleDeedDoc = GeneralFunctions::uploadFile($saleDeedDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'saledeed');
        }
        if (isset($builAgreeDoc)) {
            $builAgreeDoc = GeneralFunctions::uploadFile($builAgreeDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'BuilderAgreement');
        }
        if (isset($leaseDeedDoc)) {
            $leaseDeedDoc = GeneralFunctions::uploadFile($leaseDeedDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'leaseDeed');
        }
        if (isset($subMutLtrDoc)) {
            $subMutLtrDoc = GeneralFunctions::uploadFile($subMutLtrDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'subsMutLetter');
        }
        if (isset($otherDocDoc)) {
            $otherDocDoc = GeneralFunctions::uploadFile($otherDocDoc, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'other');
        }
        if (isset($ownerLessDocInv)) {
            $ownerLessDocInv = GeneralFunctions::uploadFile($ownerLessDocInv, $applicantNumber . '/' . $colonyCode . '/other_property/' . $block . '_' . $plot, 'ownerLessee');
        }
        if(!empty($knownAs)){
            $knownAs = $knownAs;
        } else if(!is_null($locality)){
            $knownAs = $block.'/'.$plot.'/'.$locality;
        } else if (!is_null($locality_name)){
            $knownAs = $block.'/'.$plot.'/'.$locality_name;
        }
        
        $newProperyAdded = NewlyAddedProperty::create([
            'old_property_id' => $oldPropertyId ?? null,
            'suggested_property_id' => $suggestedPropertyId ?? null,
            'user_id' => $userId,
            'applicant_number' => $applicantNumber,
            'locality' => $locality,
            'locality_name' => $locality_name,
            'block' => $block,
            'plot' => $plot,
            'flat_id' => $flat_id ?? null,
            'is_property_flat' => $is_property_flat ?? 0,
            'flat_no' => $flat_no ?? null,
            'known_as' => !empty($knownAs) ? $knownAs : null,
            'land_use_type' => $landUseType,
            'land_use_sub_type' => $landUseSubType,
            'section_id' => $section,
            'sale_deed_doc' => $saleDeedDoc,
            'builder_buyer_agreement_doc' => $builAgreeDoc,
            'lease_deed_doc' => $leaseDeedDoc,
            'substitution_mutation_letter_doc' => $subMutLtrDoc,
            'other_doc' => $otherDocDoc,
            'owner_lessee_doc' => $ownerLessDocInv,
            'status' => getStatusName('RS_PEN')
        ]);

        if ($newProperyAdded) {
            return redirect()->back()->with('success', 'Your property added successfully. Waiting for administrator approval');
        } else {
            return redirect()->back()->with('failure', 'Property not added successfully. Something went wrong');
        }
    }

    public function appointment($applicationId, $tiestamp)
    {
        $applicationNo = base64_decode($applicationId);
        $application = Application::where('application_no', $applicationNo)->first();

        if (empty($application)) {
            return redirect()->route('dashboard')->with('failure', config('messages.application.error.appNotfound'));
        }
        if ($application->created_by != Auth::id()) {
            return redirect()->route('dashboard')->with('failure', config('messages.general.error.accessDenied'));
        }
        $data['application'] = $application;
        $appointmentLink = url()->current();
        $appointmentCount = ApplicationAppointmentLink::where('link', $appointmentLink)->count();
        /* if ($appointmentCount > 1) {
            return redirect()->route('dashboard')->with('failure', config('messages.application.error.rescheduleLimitExceeded'));
        } */
        $appointmentData = ApplicationAppointmentLink::where('link', $appointmentLink)->latest()->first();
        // case when link not fouund
        if (empty($appointmentData)) {
            return redirect()->route('dashboard')->with('failure', config('messages.application.error.appointmentLinkNotFound'));
        }
        if (strtotime('today') > strtotime($appointmentData->valid_till)) {
            return redirect()->route('dashboard')->with('failure', config('messages.application.error.linkExpired'));
        }
        $data['appointmentData'] = $appointmentData;

        /** show which  dates are available in calendar */
        $minDate = date('Y-m-d', strtotime('+2 days', strtotime($appointmentData->created_at)));
        $maxDate = $appointmentData->valid_till;
        $bookedDates = ApplicationAppointmentLink::whereDate('schedule_date', '>=', $minDate)
            ->whereDate('schedule_date', '<=', $maxDate)
            ->where('link', '<>', $appointmentLink)
            ->where('is_active', 1)
            ->pluck('schedule_date')
            ->toArray();
        $holidays = Holiday::whereDate('date', '>=', $minDate)
            ->whereDate('date', '<=', $maxDate)
            ->pluck('date')
            ->toArray();

        // Calculate weekend offs (Saturday and Sunday)
        $weekendOff = [];
        $period = new DatePeriod(
            new DateTime($minDate),
            new DateInterval('P1D'),
            (new DateTime($maxDate))->modify('+1 day')
        );

        foreach ($period as $date) {
            if (in_array($date->format('N'), [6, 7])) { // 6 = Saturday, 7 = Sunday
                $weekendOff[] = $date->format('Y-m-d');
            }
        }

        $calendarData = [
            "dateFormat" => "Y-m-d",
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'disable' => array_merge($bookedDates, $holidays, $weekendOff),
            'defaultDate' => !is_null($appointmentData->schedule_date) ? [$appointmentData->schedule_date] : [],
            'bookedDates' => $bookedDates,
            'holidays' => $holidays
        ];
        $data['calendarData'] = $calendarData;

        return view('applicant.appointment', $data);
    }

    public function bookAppointment(Request $request)
    {
        $appointmentId = $request->appointmentId;
        $appointmentDate = $request->appointmentDate;
        //check if date is alrady booked
        $dateBooked = ApplicationAppointmentLink::where('schedule_date', $appointmentDate)->where('is_active', 1)->exists();
        if ($dateBooked) {
            return response()->json(['status' => false, 'message' => config('messages.application.error.appointmentTaken')]);
        }

        //check if resceduing the appointment. inactive previous appointment
        $appointmentData = ApplicationAppointmentLink::find($appointmentId);
        $rescheduleLimitExceeded = (ApplicationAppointmentLink::where('link', $appointmentData->link)->count() > 1);
        if ($rescheduleLimitExceeded) {
            return response()->json(['status' => false, 'message' => config('messages.application.error.rescheduleLimitExceeded')]);
        }

        if (!is_null($appointmentData->schedule_date)) { //reschedule appointment
            $appointmentData->update(['is_active' => 0]);
            //make new entry
            $newAppointmentData = $appointmentData->toArray();
            unset(
                $newAppointmentData['id'],
                $newAppointmentData['created_at'],
                $newAppointmentData['updated_at'],
                $newAppointmentData['is_active'],
                $newAppointmentData['schedule_date']
            );
            $newAppointment = ApplicationAppointmentLink::create(array_merge($newAppointmentData, ['schedule_date' => $appointmentDate, 'is_active' => 1]));
            return response()->json(['status' => true, 'message' => config('messages.application.success.appointmentScheduled')]);
        } else {
            // enter appointment date in alreay given link
            $appointmentData->update(['schedule_date' => $appointmentDate, 'is_active' => 1]);
            return response()->json(['status' => true, 'message' => config('messages.application.success.appointmentScheduled')]);
        }
    }
}
