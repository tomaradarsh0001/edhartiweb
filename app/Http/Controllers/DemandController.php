<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralFunctions;
use App\Models\ApplicantUserDetail;
use App\Models\CarriedDemandDetail;
use App\Models\City;
use App\Models\Country;
use App\Models\Demand;
use App\Models\DemandDetail;
use App\Models\Item;
use App\Models\OldDemand;
use App\Models\OldDemandSubhead;
use App\Models\PayerDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\PropertyMaster;
use App\Models\State;
use App\Services\ColonyService;
use App\Services\PaymentService;
use App\Services\PropertyMasterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemandController extends Controller
{
    public function createDemandView(ColonyService $colonyService)
    {
        $data['colonies'] = $colonyService->misDoneForColonies(true);
        $data['demandSubheads'] = Item::where('group_id', 7003)->where('is_active', 1)->orderBy('item_name')->get();
        return view('demand.input-form', $data);
    }
    public function EditDemand($demandId)
    {
        $demand = Demand::find($demandId);
        if (empty($demand)) {
            return redirect()->back()->with('failure', "No data found!!");
        }
        $data['demand'] = $demand;
        $colonyService = new ColonyService();
        $data['colonies'] = $colonyService->misDoneForColonies(true);
        $data['demandSubheads'] = Item::where('group_id', 7003)->where('is_active', 1)->orderBy('item_name')->get();
        return view('demand.input-form', $data);
    }
    public function ViewDemand($demandId)
    {
        $demand = Demand::find($demandId);
        if (empty($demand)) {
            return redirect()->back()->with('failure', "No data found!!");
        }
        $data['demand'] = $demand;
        $colonyService = new ColonyService();
        $data['colonies'] = $colonyService->misDoneForColonies(true);
        $data['demandSubheads'] = Item::where('group_id', 7003)->where('is_active', 1)->orderBy('item_name')->orderBy('item_name')->get();
        $data['openInReadOnlyMode'] = true;
        $data['canEdit'] = getServiceCodeById($demand->status) == "DEM_DRAFT"; //ONLY WHEN DEMAND IS STILL IN DRAFT STAGE
        $data['canApprove'] = Auth::user()->hasRole('deputy-lndo') && getServiceCodeById($demand->status) == "DEM_DRAFT"; //deputy can approve draft demands
        return view('demand.input-form', $data);
    }

    public function getExistingPropertyDemand($oldPropertyId, $formatToJson = true)
    {
        /** get unpaid or partiallly paid demands for property
        if first demand record is created then - 
        check for pending demands in old application */
        $isFirstDemand = !Demand::where('old_property_id', $oldPropertyId)->exists();
        if ($isFirstDemand) {

            $pms = new PropertyMasterService();
            $oldDemandData = $pms->getPreviousDemands($oldPropertyId);
            if ($oldDemandData) {
                /** handle the case when user checks previous pending dues but do not proceed with creating new demand -- 
                 * delete the previous saved demands and subheads
                 */
                $previousSavedDemands = OldDemand::where('property_id', $oldPropertyId)->get();
                if ($previousSavedDemands->isNotEmpty()) {
                    foreach ($previousSavedDemands as $psd) {
                        OldDemandSubhead::where('DemandID', $psd->property_id)->delete();
                    }
                    OldDemand::where('property_id', $oldPropertyId)->delete();
                }
                /** get old Demand data */
                $demands = $oldDemandData->LatestDemanddetails;
                $previousDemandData = [];
                foreach ($demands as $demand) {
                    $paidKey = collect($demand)->keys()->first(fn($key) => str_ends_with($key, 'Paid'));
                    $demandData = collect($demand)->only([
                        'PropertyID',
                        'DemandID',
                        'Amount',
                        $paidKey,
                        'Outstanding'
                    ])->mapWithKeys(function ($value, $key) use ($paidKey) {
                        return [
                            match ($key) {
                                'DemandID' => 'demand_id',
                                'PropertyID' => 'property_id',
                                'Amount' => 'amount',
                                $paidKey => 'paid_amount',
                                'Outstanding' => 'outstanding',
                                default => $key
                            } => $value
                        ];
                    })->toArray();
                    OldDemand::create($demandData);
                    $previousDemandData[] = $demandData;
                }
                $demandSubheads = $oldDemandData->SubHeadwiseBreakup;
                foreach ($demandSubheads as $oldSubhead) {
                    $oldSubheadData = collect($oldSubhead)->all();
                    OldDemandSubhead::create($oldSubheadData);
                }
                $previousDues =  ['previousDemands' => $previousDemandData, 'dues' => collect($previousDemandData)->sum('outstanding')];
            }
        }
        /** 
         get previous unpaid demand on this aplication 
         */

        $proeprtyMasterService = new PropertyMasterService();
        $findProperty = $proeprtyMasterService->propertyFromSelected($oldPropertyId);
        if ($findProperty['status'] == 'error') {
            return $formatToJson ? response()->json([
                'status' => false,
                'details' => $findProperty['details']
            ]) : false;
        } else {
            $masterProperty = $findProperty['masterProperty'];
            $propertyMasterId = $masterProperty->id;
            $childProperty = isset($findProperty['childProperty']) ? $findProperty['childProperty'] : null;
            $childId = is_null($childProperty) ? null : $childProperty->id;
            if (isset($previousDues)) {
                return  $formatToJson ? response()->json([
                    'status' => true,
                    'data' => $previousDues
                ]) : ['propertyMasterId' => $propertyMasterId, 'childId' => $childId, 'dues' => $previousDues['dues'], 'previousDemands' => $previousDues['previousDemands']];
            } else {
                $existingDemand = Demand::where('property_master_id', $propertyMasterId)
                    ->where(function ($query) use ($childId) {
                        if (is_null($childId)) {
                            return $query->whereNull('splited_property_detail_id');
                        } else {
                            return $query->where('splited_property_detail_id', $childId);
                        }
                    })
                    ->where(function ($query) {
                        return $query->whereNull('model')->orWhere('model', '<>', 'PropertyRevivisedGroundRent'); // do  not consider rgr demands
                    })
                    ->whereIn('status', [getServiceType('DEM_DRAFT'), getServiceType('DEM_PENDING'), getServiceType('DEM_PART_PAID')])
                    ->first();
                return $formatToJson ? response()->json([
                    'status' => true,
                    'data' => ['demand' => $existingDemand, 'demandDetails' => !empty($existingDemand) ? $existingDemand->demandDetails : null]
                ]) : ['propertyMasterId' => $propertyMasterId, 'childId' => $childId, 'demand' => $existingDemand];
            }
        }
    }

    public function storeDemand(Request $request)
    {
        // dd($request->all());
        try {
            return DB::transaction(function () use ($request) {
                $oldPropertyId = $request->oldPropertyId;
                $demandId = (isset($request->id) && $request->id != "") ? $request->id : null;
                if ($oldPropertyId || $demandId) {
                    if (!$demandId) {
                        $prevDues = $prevDuesDemandId = null; //initiallize to store demand subheads

                        $existingDemandData = $this->getExistingPropertyDemand($oldPropertyId, false);
                        if (isset($existingDemandData['dues'])) {
                            $prevDues = $existingDemandData['dues'];
                            $prevDuesDemandId = $existingDemandData['previousDemands'][0]['demand_id'];
                        } else {
                            $oldDemand = $existingDemandData['demand'];
                            if ($oldDemand && $oldDemand->status_code !== "DEM_DRAFT") {
                                $carriedAmount = $oldDemand->balance_amount;
                            }
                        }
                        $carriedAmount = (isset($oldDemand) && $oldDemand && $oldDemand->status_code !== "DEM_DRAFT") ? $oldDemand->balance_amount : 0;
                    } else {
                        $demand = $oldDemand = Demand::find($demandId);
                        $carriedAmount = !is_null($demand->carried_amount) ? $demand->carried_amount : 0;
                    }
                    $amounts = $request->amount;
                    $total = array_sum($amounts);
                    $netTotal = $total + $carriedAmount;
                    //create new demand
                    $fy = getFinancialYear();
                    $newDemand =  Demand::updateOrCreate(['id' => $request->id ?? 0], [
                        'unique_id' => GeneralFunctions::createUniqueDemandId($oldPropertyId),
                        'property_master_id' => $existingDemandData['propertyMasterId'] ?? $demand->property_master_id,
                        'splited_property_detail_id' =>  $existingDemandData['childId'] ?? $demand->splited_property_detail_id ?? null,
                        'flat_id' => null, //will be changed later
                        'old_property_id' => $oldPropertyId ?? $demand->old_property_id,
                        'app_no' => null,
                        'total' => $total,
                        'net_total' => $netTotal,
                        'balance_amount' => $netTotal,
                        'paid_amount' => 0,
                        'carried_amount' => $carriedAmount > 0 ? $carriedAmount : null,
                        'fy_prev_demand' => $oldDemand->current_fy ?? $demand->fy_prev_demand ?? null,
                        'current_fy' => $fy,
                        'status' => getServiceType('DEM_DRAFT'), //at first demand status is draft
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);
                    if ($newDemand) {
                        $newDemandId = $newDemand->id;
                        if (isset($request->detail_id)) {
                            $idsToKeep = array_filter($request->detail_id); // Removes null values from the array
                            // dd($idsToKeep);
                            /** If carried amount is not null then data in forwarded from old demand so not avaialble in request */
                            DemandDetail::where('demand_id', $newDemandId)->where(function ($query) {
                                return $query->whereNull('carried_amount')->orWhere('carried_amount', 0);
                            })->whereNotIn('id', $idsToKeep)->delete();
                        }
                        if (isset($oldDemand) && $oldDemand->status_code !== "DEM_DRAFT") {
                            $preveiousDemand = Demand::find($oldDemand->id);
                            if (in_array($preveiousDemand->status, [getServiceType('DEM_PENDING'), getServiceType('DEM_PART_PAID')])) { //check the status of previous demand is pending or partially paid. if yes then forward the demand to new demand, and subheads to new demand, add the remaining amount of old demand to new demand
                                $preveiousDemand->update(['status' => getServiceType('DEM_CR_FRW'), 'updated_by' => Auth::id()]); //update status of old Demand

                                //add data in carried demand Detail Table
                                CarriedDemandDetail::create([
                                    'new_demand_id' => $newDemandId,
                                    'old_demand_id' => $oldDemand->id,
                                    'carried_amount' => $carriedAmount
                                ]);

                                // create subheads for carried demand
                                $oldSubheads = $preveiousDemand->demandDetails;
                                foreach ($oldSubheads as $i => $osh) {
                                    if ($osh->balance_amount > 0) {
                                        DemandDetail::create([
                                            'demand_id' => $newDemandId,
                                            'property_master_id' => $existingDemandData['propertyMasterId'],
                                            'splited_property_detail_id' => $existingDemandData['childId'],
                                            'flat_id' => null, //will be changed later
                                            'subhead_id' => $osh->subhead_id,
                                            'total' => null,
                                            'net_total' => $osh->balance_amount,
                                            'paid_amount' => null,
                                            'balance_amount' => $osh->balance_amount,
                                            'carried_amount' => $osh->balance_amount,
                                            'duration_from' =>  $osh->duration_from,
                                            'duration_to' => $osh->duration_to,
                                            'fy' => $osh->fy,
                                            'remarks' => $osh->remark,
                                            'created_by' => Auth::id(),
                                            'updated_by' => Auth::id()
                                        ]);
                                    }
                                }
                            }
                        }
                        if (isset($prevDues)) {
                            if ($prevDues > 0) {
                                DemandDetail::create([
                                    'demand_id' => $newDemandId,
                                    'property_master_id' => $newDemand->property_master_id,
                                    'splited_property_detail_id' => $newDemand->property_master_id,
                                    'flat_id' => null, //will be changed later
                                    'subhead_id' => getServiceType('PREV_DUE'),
                                    'total' => $prevDues,
                                    'net_total' => $prevDues,
                                    'paid_amount' => null,
                                    'balance_amount' => $prevDues,
                                    'carried_amount' => null,
                                    'duration_from' =>  null,
                                    'duration_to' => null,
                                    'fy' => null,
                                    'remarks' => 'previous dues- Demand Id = ' . $prevDuesDemandId,
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id()
                                ]);
                                Demand::find($newDemandId)->update([
                                    'total' => $newDemand->total + $prevDues,
                                    'net_total' => $newDemand->net_total + $prevDues,
                                    'balance_amount' => $newDemand->balance_amount + $prevDues
                                ]);
                            }

                            OldDemand::where('demand_id', $prevDuesDemandId)->update(['new_demand_id' => $newDemandId]);
                        }
                        foreach ($request->subhead as $i => $sh) {

                            $demandDetail = DemandDetail::updateOrCreate([
                                'id' => $request->detail_id[$i] ?? 0
                            ], [
                                'demand_id' => $newDemandId,
                                'property_master_id' => $newDemand->property_master_id,
                                'splited_property_detail_id' => $newDemand->property_master_id,
                                'flat_id' => null, //will be changed later
                                'subhead_id' => $sh,
                                'total' => $amounts[$i],
                                'net_total' => $amounts[$i],
                                'paid_amount' => null,
                                'balance_amount' => $amounts[$i],
                                'carried_amount' => null,
                                'duration_from' =>  $request->duration_from[$i],
                                'duration_to' => $request->duration_to[$i],
                                'fy' => $fy,
                                'remarks' => $request->remark[$i],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ]);
                        }

                        return response()->json(['status' => true, 'message' => 'Demand created successfullly']);
                    } else {
                        return response()->json(['status' => false, 'details' => 'Demand not created.']);
                    }
                } else {
                    return response()->json(['status' => false, 'details' => config('messages.property.error.notFound')]);
                }
                return response()->json(['status' => true, 'message' => 'Demand created successfullly']);
            });
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            $response = ['status' => false, 'message' => $e->getMessage(), 'data' => 0];
            return json_encode($response);
        }
    }

    public function ApproveDemand($demandId)
    {
        $demand = Demand::find($demandId);
        if (empty($demand)) {
            return redirect()->back()->with('failure', "No data found!!");
        }
        if ($demand->status == getServiceType('DEM_DRAFT')) {
            $demand->update(['status' => getServiceType('DEM_PENDING'), 'updated_by' => Auth::id()]);

            //send email to peoprty owner abt new demand created
            return redirect()->route('demandList')->with('success', "Demand approved successfully");
        } else {
            return redirect()->back()->with('failure', "Can not approve this demand");
        }
    }

    public function withdrawDemand($demandId)
    {
        $demand = Demand::find($demandId);
        if (empty($demand)) {
            return redirect()->back()->with('failure', "No data found!!");
        }
        if ($demand->carried_amount && $demand->carried_amount > 0) {
            $carriedDetails = CarriedDemandDetail::where('new_demand_id', $demandId)->first();
            if (!empty($carriedDetails)) {
                $oldDemand = Demand::find($carriedDetails->old_demand_id);
                if (!empty($oldDemand)) {
                    $statusToUpdate = getServiceType('DEM_PART_PAID');
                    if ($oldDemand->net_total == $oldDemand->balance_amount) {
                        $statusToUpdate = getServiceType('DEM_PENDING');
                    }
                    $oldDemand->update(['status' => $statusToUpdate, 'updated_by' => Auth::id()]);
                } else {
                    return redirect()->back()->with('failure', "Something went wrong. Required data is missing");
                }
            } else {
                return redirect()->back()->with('failure', "Something went wrong. Required data is missing");
            }
        }
        $demand->update(['status' => getServiceType('DEM_WD'), 'updated_by' => Auth::id()]);
        return redirect()->back()->with('success', "Demand withdrawn successfully");
    }


    public function index()
    {
        $demands = Demand::latest()->get();
        return view('demand.index', compact('demands'));
    }

    public function applicantPendingDemands()
    {
        $pendingDemands = GeneralFunctions::getUserDemandData(false, true);
        return view('demand.applicant.index', ['demands' => $pendingDemands]);
    }
    public function applicantViewDemand($demandId)
    {
        $demand = Demand::find($demandId);
        if (empty($demand)) {
            return redirect()->back()->with('failure', "No data found!!");
        }
        return view('demand.applicant.view', ['demand' => $demand]);
    }

    public function applicantPayForDemand($demandId)
    {
        $demand = Demand::find($demandId);
        if (empty($demand)) {
            return redirect()->back()->with('failure', "No data found!!");
        }
        if (in_array(getServiceCodeById($demand->status), ["DEM_PART_PAID", "DEM_PENDING"])) {

            $addressDropdownData = getAddressDropdownData();

            return view('demand.applicant.payment-form', ['demand' => $demand, ...$addressDropdownData]);
        } else {
            return redirect()->back()->with('failure', "Can not make payment for this demand");
        }
    }

    public function applicantDemandPayment(Request $request, PaymentService $paymentService)
    {
        if (!empty($request->demand_id)) {
            $demandId = $request->demand_id;
            $demand = Demand::find($demandId);
            if (empty($demand)) {
                return redirect()->back()->with('faliure', 'Given demand not found');
            }
        } else {
            return redirect()->back()->with('faliure', 'Demand not given');
        }
        $propertyMasterId = $demand->property_master_id;
        if (is_null($demand->splited_property_detail_id)) {
            $master_old_property_id = $demand->old_property_id;
            $splited_old_property_id = null;
        } else {
            $masterProperty = PropertyMaster::find($propertyMasterId);
            $master_old_property_id = $masterProperty->old_propert_id;
            $splited_old_property_id = $demand->old_property_id;
        }
        $paidAmount = array_sum($request->paid_amount);
        $uniquePayemntId = 'DEM' . date('YmdHis');
        $payment = Payment::create([
            'property_master_id' => $propertyMasterId,
            'type' => getServiceType('PAY_DEMAND'),
            'demand_id' => $demandId,
            'payment_mode' => getServiceType($request->payment_mode),
            'unique_payment_id' => $uniquePayemntId,
            'splited_property_detail_id' => $demand->splited_property_detail_id,
            'master_old_property_id' => $master_old_property_id,
            'splited_old_property_id' => $splited_old_property_id,
            'amount' => $paidAmount,
            'status' => 1,
            'created_by' => Auth::check() ? Auth::id() : null
        ]);

        if ($payment) {

            //save payer details
            GeneralFunctions::savePayerDetails($request->all(), $payment->id);

            //save payment subheads
            foreach ($request->subhead_id as $i => $subhead) {
                if (isset($request->paid_amount[$i]) && $request->paid_amount[$i] != "") {
                    $saveDetail = PaymentDetail::create([
                        'payment_id' => $payment->id,
                        'demand_id' => $demandId,
                        'subhead_id' => $subhead,
                        'paid_amount' => $request->paid_amount[$i],
                    ]);
                }
            }
            // Payment 
            list($countryName, $stateName, $cityName) =  GeneralFunctions::getAddressNames($request->only('country', 'state', 'city'));

            $orderCode = $uniquePayemntId;
            // $orderCode = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
            $payementData = [
                'order_code' => $orderCode,
                'merchant_batch_code' => $orderCode,
                'installation_id' => '11136',
                'amount' => $paidAmount,
                'currency_code' => "INR",
                'order_content' => '23092',
                'payemnt_type_id' => config('constants.payment_type_id'),
                'email' => $request->payer_email,
                'first_name' => $request->payer_first_name,
                'last_name' => $request->payer_last_name,
                'mobile' => $request->payer_mobile,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'postal_code' => $request->postal_code,
                'region' => $request->region,
                'city' => $cityName,
                'state' => $stateName,
                'country' => $countryName,
            ];
            // dd($payementData);
            $transaction = $paymentService->makePayemnt($payementData);
            // return redirect()->back()->with('success', 'Data saved successfully');
        }
    }
}
