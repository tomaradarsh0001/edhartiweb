<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyMaster;
use App\Models\PropertyLeaseDetail;
use App\Models\PropertyTransferredLesseeDetail;
use App\Models\SplitedPropertyDetail;
use App\Services\PropertyMasterService;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
    public function changeLeaseExpirationDate(Request $request)
    {

        $property_details = ['49461', '75648', '50466', '63880', '48862', '63886', '75647', '50425', '20904', '49390', '49460', '49915', '49385', '63860', '27008', '25889', '64008', '64010', '21118', '64035', '64046', '64045', '64044', '64053', '49405', '64038', '64037', '64039', '26365', '64041', '64040', '66427', '66943', '38405', '78546', '76988', '43885', '60785', '62073', '22635', '28572', '29713', '56191', '54423', '56362', '38089'];
        for ($i = 0; $i < count($property_details); $i++) {

            $master_property  = PropertyMaster::where('old_propert_id', $property_details[$i])->first();

            $lease_details = PropertyLeaseDetail::where('property_master_id', $master_property->id)->first();

            if ($lease_details) {
                $date_of_execution = new \DateTime($lease_details->doe);
                $date_of_execution->modify('+99 years'); // 99 years ahead
                $lease_details->date_of_expiration = $date_of_execution->format('Y-m-d');
                $lease_details->save();
                echo 'PID: ' . $property_details[$i] . '--DOE: ' . $lease_details->doe . '--DOEx ' . $lease_details->date_of_expiration . '<br>';
            }
        }
    }

    /** function added by Nitin to get basic detail of a property on 20dec2024 */

    public function propertyBasicdetail(Request $request)
    {
        // dd($request->all());
        $propertyId = $request->property_id;
        $returnSplitedProp = false;
        $splitedPropId = null;
        $searchIncolumn = 'old_propert_id'; // for non splited property property is found by old property id when selected from colony block plot dropdown
        $statusColumn = "status";
        if (strpos($propertyId, '_')) { // // for property master_id.'_.child_id
            $idArr = explode('_', $propertyId);
            $propertyId = $idArr[0];
            $splitedPropId = $idArr[1];
            $returnSplitedProp = true;

            $searchIncolumn = 'id';
        }
        $property = PropertyMaster::where($searchIncolumn, $propertyId)->first();
        if (empty($property)) { // if property not found in masters table
            /** If property not found in masters table then check in splitedProperty table */
            $property = SplitedPropertyDetail::where('old_property_id', $propertyId)->first();
            if (empty($property)) {

                return ['status' => 'error', 'message' => 'given property not found'];
            }
            $splitedPropId = $property->id;
            $returnSplitedProp = true;
            $propertyMasterId = $property->property_master_id;
            $statusColumn = 'property_status';
        } else {
            $propertyMasterId = $property->id;
        }
        $pms = new PropertyMasterService();
        $propertyIsInUserSection = $pms->checkPropertyIsInUserSectoin($propertyMasterId);
        if (!$propertyIsInUserSection) {
            return ['status' => 'error', 'message' => 'You dont have access to this property'];
        }
        if (array_key_exists('is_joint_property', $property->getAttributes())) { //check if is_joint_property exist in property, only available in property masters
            if (!is_null($property->is_joint_property)) {
                $statusColumn = 'property_status';
                $children = SplitedPropertyDetail::where('property_master_id', $propertyMasterId)->where($statusColumn, '951')->get();
                if ($children->count() == 0) {
                    return ['status' => 'error', 'message' => 'Can not Process this property. Please check property details'];
                } else {
                    $propertyData = [];
                    foreach ($children as $child) {
                        $propertyData[] = $this->preparePropertyDetails($child->master, true, $child->id, $propertyMasterId);
                    }
                }
            } else {
                $propertyData = $this->preparePropertyDetails($property, false, null, $propertyMasterId);
            }
        } else {
            $propertyData = $this->preparePropertyDetails($property->master, true, $splitedPropId, $propertyMasterId);
        }
        return ['status' => 'success', 'data' => $propertyData];
    }

    private function preparePropertyDetails($property, $returnSplitedProp, $splitedPropId, $propertyMasterId)
    {
        $propertyMasterService = new PropertyMasterService();
        $propertyData = $propertyMasterService->formatPropertyDetails($property, $returnSplitedProp, $splitedPropId);
        $trasferDetails = PropertyTransferredLesseeDetail::where('property_master_id', $propertyMasterId)->when(is_null($splitedPropId), function ($query) {
            return $query->whereNull('splited_property_detail_id');
        }, function ($query) use ($splitedPropId) {
            return $query->where('splited_property_detail_id', $splitedPropId);
        })->select([
            'property_master_id',
            'splited_property_detail_id',
            'batch_transfer_id',
            'process_of_transfer',
            'transferDate'
        ])->selectRaw("GROUP_CONCAT(lessee_name SEPARATOR ', ') as lesse_name")->groupBy('property_master_id', 'splited_property_detail_id', 'batch_transfer_id', 'process_of_transfer', 'transferDate')->orderBy('transferDate')->get();
        $propertyData['trasferDetails'] = $trasferDetails;
        return $propertyData;
    }

    public function countryStateList($countryId)
    {
        $states = DB::table('states')->where('country_id', $countryId)->get();
        return response()->json(['data' => $states]);
    }
    public function stateCityList($stateId)
    {
        $cities = DB::table('cities')->where('state_id', $stateId)->get();
        return response()->json(['data' => $cities]);
    }
}
