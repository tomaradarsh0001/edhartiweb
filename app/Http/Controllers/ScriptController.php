<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PropertyMaster;
use App\Models\LndoLandRate;
use App\Models\CircleLandRate;
use App\Models\PropertyLeaseDetail;
use App\Models\CurrentLesseeDetail;
use App\Models\PropertyTransferredLesseeDetail;
use App\Models\SplitedPropertyDetail;
use App\Models\CircleResidentialLandRate;
use App\Models\LndoResidentialLandRate;
use App\Models\CircleCommercialLandRate;
use App\Models\LndoCommercialLandRate;
use App\Models\CircleInstitutionalLandRate;
use App\Models\LndoInstitutionalLandRate;
use App\Models\CircleIndustrialLandRate;
use App\Models\LndoIndustrialLandRate;
use App\Models\PropertyLeaseDetailHistory;
use DateTime;
use App\Models\UnallottedPropertyDetail;
use Illuminate\Support\Facades\Log;

class ScriptController extends Controller
{

    //function to update new Cirle and LNDO rates on basis of property type - SOURAV CHAUHAN (27/Nov/2024)
    public function updateLandRates()
    {
        //query for getting records from this id to that
        $properties = PropertyMaster::whereBetween('id', array('20001', '30000'))->get();

        //Properties colony Wise
        // $properties = PropertyMaster::where('new_colony_name',173)->get();

        // $properties = PropertyMaster::whereIn('new_colony_name', ['416', '194'])->get();
        foreach ($properties as $property) {
            $propertyStatus = $property->status;
            $propertyId = $property->id;
            if ($propertyStatus == 1476) {
                echo "Unallotted Property ID:- " . $propertyId . " <b> Not Updated</b><br>";
            } else {
                $propertyType = $property->property_type;
                $newColonyName = $property->new_colony_name;
                $circleRate = null;
                $lndoRate = null;
                switch ($propertyType) {
                    case '47': //Residential
                        $circleRate = Self::fetchLatestLandRate(CircleResidentialLandRate::class, $newColonyName);
                        $lndoRate = Self::fetchLatestLandRate(LndoResidentialLandRate::class, $newColonyName);
                        // $dataCir = CircleLandRate::where("old_colony_id", $newColonyName)
                        //         ->orderBy('date_from', 'desc')
                        //         ->first();
                        // $circleRate = $dataCir ? $dataCir['residential_land_rate'] : null;


                        // $dataLndo = LndoLandRate::where("old_colony_id", $newColonyName)
                        //         ->orderBy('date_from', 'desc')
                        //         ->first();
                        // $lndoRate = $dataLndo ? $dataLndo['residential_land_rate'] : null;
                        break;
                    case '48': //Commercial
                    case '72': //Mixed
                        $circleRate = Self::fetchLatestLandRate(CircleCommercialLandRate::class, $newColonyName);
                        $lndoRate = Self::fetchLatestLandRate(LndoCommercialLandRate::class, $newColonyName);
                        break;
                    case '49': //Institutional
                        $circleRate = Self::fetchLatestLandRate(CircleInstitutionalLandRate::class, $newColonyName);
                        $lndoRate = Self::fetchLatestLandRate(LndoInstitutionalLandRate::class, $newColonyName);
                        break;
                    case '469': //industrial
                        $circleRate = Self::fetchLatestLandRate(CircleIndustrialLandRate::class, $newColonyName);
                        $lndoRate = Self::fetchLatestLandRate(LndoIndustrialLandRate::class, $newColonyName);
                        break;
                }
                $propertyLeaseDetails = PropertyLeaseDetail::where('property_master_id', $propertyId)->first();
                $plotAreaInSqm = round($propertyLeaseDetails['plot_area_in_sqm'], 2);
                // dd($lndoRate , (float)$plotAreaInSqm);
                if ($lndoRate !== null) {
                    $propertyLeaseDetails->plot_value = round($lndoRate * (float)$plotAreaInSqm, 2);
                }
                if ($circleRate !== null) {
                    $propertyLeaseDetails->plot_value_cr = round($circleRate * (float)$plotAreaInSqm, 2);
                }
                if ($propertyLeaseDetails->save()) {
                    echo "Parent Property ID:- " . $propertyId . " <b>Updated</b><br>";
                    if ($property->is_joint_property) {
                        $childPropertyDetails = SplitedPropertyDetail::where('property_master_id', $propertyId)->get();
                        if ($childPropertyDetails) {
                            foreach ($childPropertyDetails as $childPropertyDetail) {
                                $childPropertyId = $childPropertyDetail['id'];
                                $childPlotAreaInSqm = round($childPropertyDetail['area_in_sqm'], 2);
                                if ($lndoRate !== null) {
                                    $childPropertyDetail->plot_value = round($lndoRate * (float)$childPlotAreaInSqm, 2);
                                }
                                if ($circleRate !== null) {

                                    $childPropertyDetail->plot_value_cr = round($circleRate * (float)$childPlotAreaInSqm, 2);
                                }
                                if ($childPropertyDetail->save()) {
                                    echo "Child Property ID:- " . $childPropertyId . " <b>Updated</b><br>";
                                } else {
                                    echo "Child Property ID:- " . $childPropertyId . " <b>not Updated ----------------------**********<b><br>";
                                }
                            }
                        }
                    }
                } else {
                    echo "Parent Property ID:- " . $propertyId . " <b>not Updated ----------------------**********<b><br>";
                }
            }
        }
    }

    //to fetch land ates from different models
    function fetchLatestLandRate($modelClass, $colonyId)
    {
        $data = $modelClass::where("colony_id", $colonyId)
            ->orderBy('date_from', 'desc')
            ->first();

        return $data ? $data->land_rate : 0;
    }

    public function updateLandValue($id)
    {


        $colonyId = $id;
        $properties = PropertyMaster::where('new_colony_name', $colonyId)->get();

        $lndoRate = LndoLandRate::where("old_colony_id", $colonyId)
            ->orderBy('date_from', 'desc')
            ->first();

        $circleRate = CircleLandRate::where("old_colony_id", $colonyId)
            ->orderBy('date_from', 'desc')
            ->first();

        if ($lndoRate || $circleRate) {
            foreach ($properties as $property) {
                $propertyLeaseDetails = PropertyLeaseDetail::where('property_master_id', $property->id)->first();

                if ($propertyLeaseDetails) {
                    $propertyType = $propertyLeaseDetails['is_land_use_changed'] == 1
                        ? $propertyLeaseDetails['property_type_at_present']
                        : $propertyLeaseDetails['property_type_as_per_lease'];

                    $lndoRateInv = null;
                    $circleRateInv = null;

                    switch ($propertyType) {
                        case '47':
                            $lndoRateInv = $lndoRate ? $lndoRate['residential_land_rate'] : null;
                            $circleRateInv = $circleRate ? $circleRate['residential_land_rate'] : null;
                            break;
                        case '48':
                            $lndoRateInv = $lndoRate ? $lndoRate['commercial_land_rate'] : null;
                            $circleRateInv = $circleRate ? $circleRate['commercial_land_rate'] : null;
                            break;
                        case '49':
                            $lndoRateInv = $lndoRate ? $lndoRate['institutional_land_rate'] : null;
                            $circleRateInv = $circleRate ? $circleRate['institutional_land_rate'] : null;
                            break;
                    }
                    $plotAreaInSqm = round($propertyLeaseDetails['plot_area_in_sqm'], 2);
                    if ($lndoRateInv !== null) {
                        $propertyLeaseDetails->plot_value = round($lndoRateInv * $plotAreaInSqm, 2);
                    }
                    if ($circleRateInv !== null) {
                        $propertyLeaseDetails->plot_value_cr = round($circleRateInv * $plotAreaInSqm, 2);
                    }
                    $propertyLeaseDetails->save();
                } else {
                    return "Property lease details not found for property ID: {$property->id}";
                }
            }

            return "Land value updated successfully";
        } else {
            return "Colony ID not available in LNDO Rates or Circle Rates Table";
        }
    }

    //For updating the land values according to LNDO rates and Circle Rates in Splited Table
    public function updateLandValueInChild($id)
    {
        $colonyId = $id;
        $properties = PropertyMaster::where('is_joint_property', 1)->where('new_colony_name', $colonyId)->get();

        $lndoRate = LndoLandRate::where("old_colony_id", $colonyId)
            ->orderBy('date_from', 'desc')
            ->first();

        $circleRate = CircleLandRate::where("old_colony_id", $colonyId)
            ->orderBy('date_from', 'desc')
            ->first();

        if ($lndoRate || $circleRate) {
            foreach ($properties as $property) {
                // $propertyLeaseDetails = PropertyLeaseDetail::where('property_master_id', $property->id)->first();
                $childPropertyDetails = SplitedPropertyDetail::where('property_master_id', $property->id)->get();
                if ($childPropertyDetails) {
                    foreach ($childPropertyDetails as $childPropertyDetail) {

                        $propertyType = $property->property_type;
                        $lndoRateInv = null;
                        $circleRateInv = null;
                        switch ($propertyType) {
                            case '47':
                                $lndoRateInv = $lndoRate ? $lndoRate['residential_land_rate'] : null;
                                $circleRateInv = $circleRate ? $circleRate['residential_land_rate'] : null;
                                break;
                            case '48':
                                $lndoRateInv = $lndoRate ? $lndoRate['commercial_land_rate'] : null;
                                $circleRateInv = $circleRate ? $circleRate['commercial_land_rate'] : null;
                                break;
                            case '49':
                                $lndoRateInv = $lndoRate ? $lndoRate['institutional_land_rate'] : null;
                                $circleRateInv = $circleRate ? $circleRate['institutional_land_rate'] : null;
                                break;
                        }

                        $plotAreaInSqm = round($childPropertyDetail['area_in_sqm'], 2);
                        if ($lndoRateInv !== null) {
                            $childPropertyDetail->plot_value = round($lndoRateInv * $plotAreaInSqm, 2);
                        }
                        if ($circleRateInv !== null) {

                            $childPropertyDetail->plot_value_cr = round($circleRateInv * $plotAreaInSqm, 2);
                        }
                        $childPropertyDetail->save();
                    }
                } else {
                    return "Property not available in Splited Table for property ID: {$property->id}";
                }
            }

            return "Land value updated successfully";
        } else {
            return "Colony ID not available in LNDO Rates or Circle Rates Table";
        }
    }

    //for updating the current lessee in seperate table
    public function updateCurrentLessee($id)
    {
        try {
            // $colonyId = $id;
            // $properties = PropertyMaster::where('new_colony_name', $colonyId)->get();
            $propertyId = $id;
            $properties = PropertyMaster::where('new_colony_name', $propertyId)->get();

            if ($properties->isEmpty()) {
                return "No properties found for the given colony ID.";
            }

            foreach ($properties as $property) {

                //if property is joint
                if ($property->is_joint_property == 1) {

                    $splitedPropertyDetails = SplitedPropertyDetail::where('property_master_id', $property->id)->get();
                    foreach ($splitedPropertyDetails as $splitedPropertyDetail) {
                        // dd('Inside foreach');
                        // Property Details
                        $propertyMasterIdChild = $property->id;
                        $splitedPropertyDetailId = $splitedPropertyDetail->id;
                        $oldPropertyIdChild = $splitedPropertyDetail->old_property_id;
                        $propertyStatusChild = $splitedPropertyDetail->property_status;
                        $currentArea = $splitedPropertyDetail->current_area;
                        $areaInSqm = $splitedPropertyDetail->area_in_sqm;
                        $unitChild = $splitedPropertyDetail->unit;
                        $presentlyKnownAsChild = $splitedPropertyDetail->presently_known_as;
                        $createdByChild = $splitedPropertyDetail->created_by;


                        // Find lessees
                        $latestBatchIdChild = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)->where('splited_property_detail_id', $splitedPropertyDetailId)->max('batch_transfer_id');
                        // if ($latestBatchIdChild === null) {
                        //     return "No lessee details found for property ID: {$property->id} and Splitted property id {$splitedPropertyDetailId}";
                        // }

                        if ($latestBatchIdChild) {

                            $lesseesWithLatestBatchIdChild = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)
                                ->where('splited_property_detail_id', $splitedPropertyDetailId)
                                ->where('batch_transfer_id', $latestBatchIdChild)
                                ->pluck('lessee_name')
                                ->toArray();

                            $lesseesNamesChild = implode(",", $lesseesWithLatestBatchIdChild);
                            $currentLesseeDetailChild = CurrentLesseeDetail::where('property_master_id', $property->id)->where('splited_property_detail_id', $splitedPropertyDetail->id)->first();
                            // If current lessee details already saved
                            if (!empty($currentLesseeDetailChild)) {
                                if ($currentLesseeDetailChild['lessees_name'] != $lesseesNamesChild) {
                                    $currentLesseeDetailChild->property_status = $propertyStatusChild;
                                    $currentLesseeDetailChild->lessees_name = $lesseesNamesChild;
                                    $currentLesseeDetailChild->property_known_as = $presentlyKnownAsChild;
                                    $currentLesseeDetailChild->area = $currentArea;
                                    $currentLesseeDetailChild->unit = $unitChild;
                                    $currentLesseeDetailChild->area_in_sqm = $areaInSqm;
                                    $currentLesseeDetailChild->save();
                                }
                            } else {
                                CurrentLesseeDetail::create([
                                    'property_master_id' => $propertyMasterIdChild,
                                    'splited_property_detail_id' => $splitedPropertyDetailId,
                                    'old_property_id' => $oldPropertyIdChild,
                                    'property_status' => $propertyStatusChild,
                                    'lessees_name' => $lesseesNamesChild,
                                    'property_known_as' => $presentlyKnownAsChild,
                                    'area' => $currentArea,
                                    'unit' => $unitChild,
                                    'area_in_sqm' => $areaInSqm,
                                    'created_by' => $createdByChild
                                ]);
                            }
                        }
                    }
                } else {

                    // Property Details
                    $propertyMasterId = $property->id;
                    $oldPropertyId = $property->old_propert_id;
                    $propertyStatus = $property->status;

                    // Find lessees
                    $latestBatchId = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)->max('batch_transfer_id');
                    if ($latestBatchId === null) {
                        return "No lessee details found for property ID: {$property->id}";
                    }

                    $lesseesWithLatestBatchId = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)
                        ->where('batch_transfer_id', $latestBatchId)
                        ->pluck('lessee_name')
                        ->toArray();

                    $lesseesNames = implode(",", $lesseesWithLatestBatchId);

                    // Details from lease details
                    $leaseDetails = PropertyLeaseDetail::where('property_master_id', $property->id)->first();
                    if ($leaseDetails === null) {
                        return "No lease details found for property ID: {$property->id}";
                    }

                    $presentlyKnown = $leaseDetails['presently_known_as'] ?? '';
                    $plotArea = $leaseDetails['plot_area'] ?? 0;
                    $unit = $leaseDetails['unit'] ?? '';
                    $plotAreaInSqm = round($leaseDetails['plot_area_in_sqm'] ?? 0, 2);
                    $createdBy = $leaseDetails['created_by'] ?? '';

                    $currentLesseeDetail = CurrentLesseeDetail::where('property_master_id', $property->id)->first();

                    // If current lessee details already saved
                    if (!empty($currentLesseeDetail)) {
                        if ($currentLesseeDetail['lessees_name'] != $lesseesNames) {
                            $currentLesseeDetail->property_status = $propertyStatus;
                            $currentLesseeDetail->lessees_name = $lesseesNames;
                            $currentLesseeDetail->property_known_as = $presentlyKnown;
                            $currentLesseeDetail->area = $plotArea;
                            $currentLesseeDetail->unit = $unit;
                            $currentLesseeDetail->area_in_sqm = $plotAreaInSqm;
                            $currentLesseeDetail->save();
                        }
                    } else {
                        CurrentLesseeDetail::create([
                            'property_master_id' => $propertyMasterId,
                            'splited_property_detail_id' => null,
                            'old_property_id' => $oldPropertyId,
                            'property_status' => $propertyStatus,
                            'lessees_name' => $lesseesNames,
                            'property_known_as' => $presentlyKnown,
                            'area' => $plotArea,
                            'unit' => $unit,
                            'area_in_sqm' => $plotAreaInSqm,
                            'created_by' => $createdBy
                        ]);
                    }
                }
            }

            return "Current lessee details updated successfully.";
        } catch (\Exception $e) {
            return "An error occurred: " . $e->getMessage();
        }
    }

    // To convert the area to square meter
    public function convertToSquareMeter($value, $fromUnit)
    {
        if ($fromUnit != 29) {
            $conversionFactors = [
                '27' => 4046.86,
                '28' => 0.092903,
                '30' => 0.836127,
                '589' => 10000,
            ];

            // Convert value to square meters
            if (array_key_exists($fromUnit, $conversionFactors)) {
                return $value * $conversionFactors[$fromUnit];
            } else {
                return null;
            }
        } else {
            return $value;
        }
    }


    //function to update unit and area for property - LALIT TIWARI (08/Jan/2025)
    public function updatePropertyAreaUnit()
    {
        // Property Ids Array LALIT TIWARI (08/Jan/2025)
        // $propertyIds = [10255, 27959];
        // $unitToCovert = 30;
        $propertyIds = [];
        $unitToCovert = null;
        //Quary for fething record from Property Master LALIT TIWARI (08/Jan/2025)
        $properties = PropertyMaster::whereIn('id', $propertyIds)->get();
        if (count($properties) > 0) {
            foreach ($properties as $property) {
                $circleRate = $lndoRate = null;
                $propertyStatus = $property->status; // Fetch Property Status 
                $propertyId = $property->id; // Fetch Property Id 
                $colonyId = $property->new_colony_name; // Fetch Colony Id
                $propertyType = $property->property_type; // Fetch Property Type
                if ($propertyStatus == 1476) {
                    //Fetch Unallotted Property Details LALIT TIWARI (08/Jan/2025)
                    $unallotedPropertyObj = UnallottedPropertyDetail::where('property_master_id', $propertyId)->first();
                    if (!empty($unallotedPropertyObj)) {
                        Log::info('Unalloted Property Before Updated:', ['unalloted_property_id' => $unallotedPropertyObj->id, 'property_master_id' => $propertyId, 'unit' => $unallotedPropertyObj->unit, 'plot_area_in_sqm' => $unallotedPropertyObj->plot_area_in_sqm, 'plot_value' => $unallotedPropertyObj->plot_value, 'plot_value_cr' => $unallotedPropertyObj->plot_value_cr]);
                        echo "Unalloted Property Before Updated: unalloted_property_id = " . $unallotedPropertyObj->id . ",property_master_id = " . $propertyId . ", unit = " . $unallotedPropertyObj->unit . ", plot_area_in_sqm = " . $unallotedPropertyObj->plot_area_in_sqm . ", plot_value = " . $unallotedPropertyObj->plot_value . ", plot_value_cr = " . $unallotedPropertyObj->plot_value_cr . "<br>";

                        //Convert plot area into square meter by given unit LALIT TIWARI (08/Jan/2025)
                        $plotAreaInSqmForUnallotedProperty = self::convertToSquareMeter($unallotedPropertyObj->plot_area,  $unitToCovert);

                        // Calculated Circle & Lndo Rate  LALIT TIWARI (08/Jan/2025)
                        $circleRateForUnallotedProperty = Self::fetchLatestLandRate(CircleResidentialLandRate::class, $colonyId);
                        $lndoRateForUnallotedProperty = Self::fetchLatestLandRate(LndoResidentialLandRate::class, $colonyId);
                        $plotAreaRoundedForUnallotedProperty = round($plotAreaInSqmForUnallotedProperty, 2);
                        if ($lndoRateForUnallotedProperty !== null) {
                            $plotValueForUnallotedProperty = round($lndoRateForUnallotedProperty * $plotAreaRoundedForUnallotedProperty, 2);
                        }
                        if ($circleRateForUnallotedProperty !== null) {
                            $plotValueCrForUnallotedProperty = round($circleRateForUnallotedProperty * $plotAreaRoundedForUnallotedProperty, 2);
                        }
                        $unallotedPropertyObj->unit = $unitToCovert;
                        $unallotedPropertyObj->plot_area_in_sqm = $plotAreaInSqmForUnallotedProperty;
                        $unallotedPropertyObj->plot_value = $plotValueForUnallotedProperty;
                        $unallotedPropertyObj->plot_value_cr = $plotValueCrForUnallotedProperty;
                        //Undate record in to Unalloted Property Details Table LALIT TIWARI (08/Jan/2025)
                        $unallotedPropertyObj->save();
                        Log::info('Unalloted Property After Updated:', ['unalloted_property_id' => $unallotedPropertyObj->id, 'property_master_id' => $propertyId, 'unit' => $unitToCovert, 'plot_area_in_sqm' => $plotAreaRoundedForUnallotedProperty, 'plot_value' => $plotValueForUnallotedProperty, 'plot_value_cr' => $plotValueCrForUnallotedProperty]);
                        echo "Unalloted Property After Updated: unalloted_property_id = " . $unallotedPropertyObj->id . ",property_master_id = " . $propertyId . ", unit = " . $unitToCovert . ", plot_area_in_sqm = " . $plotAreaRoundedForUnallotedProperty . ", plot_value = " . $plotValueForUnallotedProperty . ", plot_value_cr = " . $plotValueCrForUnallotedProperty . "<br>";
                    }
                } else {
                    switch ($propertyType) {
                        case '47': //Residential
                            $circleRate = Self::fetchLatestLandRate(CircleResidentialLandRate::class, $colonyId);
                            $lndoRate = Self::fetchLatestLandRate(LndoResidentialLandRate::class, $colonyId);
                            break;
                        case '48': //Commercial
                        case '72': //Mixed
                            $circleRate = Self::fetchLatestLandRate(CircleCommercialLandRate::class, $colonyId);
                            $lndoRate = Self::fetchLatestLandRate(LndoCommercialLandRate::class, $colonyId);
                            break;
                        case '49': //Institutional
                            $circleRate = Self::fetchLatestLandRate(CircleInstitutionalLandRate::class, $colonyId);
                            $lndoRate = Self::fetchLatestLandRate(LndoInstitutionalLandRate::class, $colonyId);
                            break;
                        case '469': //industrial
                            $circleRate = Self::fetchLatestLandRate(CircleIndustrialLandRate::class, $colonyId);
                            $lndoRate = Self::fetchLatestLandRate(LndoIndustrialLandRate::class, $colonyId);
                            break;
                    }
                    $propertyLeaseDetails = PropertyLeaseDetail::where('property_master_id', $propertyId)->first();
                    Log::info('Property Before Updated:', ['property_lease_details_id' => $propertyLeaseDetails->id, 'property_master_id' => $propertyId, 'unit' => $propertyLeaseDetails->unit, 'plot_area_in_sqm' => $propertyLeaseDetails->plot_area_in_sqm, 'plot_value' => $propertyLeaseDetails->plot_value, 'plot_value_cr' => $propertyLeaseDetails->plot_value_cr]);
                    echo "Property Before Updated: property_lease_details_id = " . $propertyLeaseDetails->id . ",property_master_id = " . $propertyId . ", unit = " . $propertyLeaseDetails->unit . ", plot_area_in_sqm = " . $propertyLeaseDetails->plot_area_in_sqm . ", plot_value =" . $propertyLeaseDetails->plot_value . ", plot_value_cr = " . $propertyLeaseDetails->plot_value_cr . "<br>";
                    $plotAreaForPropertyLeaseDetails = self::convertToSquareMeter($propertyLeaseDetails['plot_area'],  $unitToCovert);
                    $plotAreaInSqmForPropertyLeaseDetails = round($plotAreaForPropertyLeaseDetails, 2);
                    if ($lndoRate !== null) {
                        $propertyPlotValueForPropertyLeaseDetails = round($lndoRate * (float)$plotAreaInSqmForPropertyLeaseDetails, 2);
                    }
                    if ($circleRate !== null) {
                        $propertyPlotValueCrForPropertyLeaseDetails = round($circleRate * (float)$plotAreaInSqmForPropertyLeaseDetails, 2);
                    }
                    $propertyLeaseDetails->unit = $unitToCovert;
                    $propertyLeaseDetails->plot_area_in_sqm = $plotAreaInSqmForPropertyLeaseDetails;
                    $propertyLeaseDetails->plot_value = $propertyPlotValueForPropertyLeaseDetails;
                    $propertyLeaseDetails->plot_value_cr = $propertyPlotValueCrForPropertyLeaseDetails;

                    if ($propertyLeaseDetails->save()) {
                        Log::info('Property After Updated:', ['property_lease_details_id' => $propertyLeaseDetails->id, 'property_master_id' => $propertyId, 'unit' => $unitToCovert, 'plot_area_in_sqm' => $plotAreaInSqmForPropertyLeaseDetails, 'plot_value' => $propertyPlotValueForPropertyLeaseDetails, 'plot_value_cr' => $propertyPlotValueCrForPropertyLeaseDetails]);
                        echo "Property After Updated: property_lease_details_id = " . $propertyLeaseDetails->id . ",property_master_id = " . $propertyId . ", unit = " . $unitToCovert . ", plot_area_in_sqm = " . $plotAreaInSqmForPropertyLeaseDetails . ", plot_value =" . $propertyPlotValueForPropertyLeaseDetails . ", plot_value_cr = " . $propertyPlotValueCrForPropertyLeaseDetails . "<br>";

                        if ($property->is_joint_property) {
                            $childPropertyDetails = SplitedPropertyDetail::where('property_master_id', $propertyId)->get();
                            if (count($childPropertyDetails) > 0) {
                                foreach ($childPropertyDetails as $childPropertyDetail) {
                                    $childPropertyId = $childPropertyDetail['id'];
                                    $plotAreaInSqmSplittedPropertyDetails = self::convertToSquareMeter($childPropertyDetail['current_area'],  $unitToCovert);
                                    $childPlotAreaInSqmSplittedPropertyDetails = round($plotAreaInSqmSplittedPropertyDetails, 2);
                                    if ($lndoRate !== null) {
                                        $childPropertyPlotValueSplittedProperty = round($lndoRate * (float)$childPlotAreaInSqmSplittedPropertyDetails, 2);
                                    }
                                    if ($circleRate !== null) {
                                        $childPropertyPlotValueCrSplittedProperty = round($circleRate * (float)$childPlotAreaInSqmSplittedPropertyDetails, 2);
                                    }
                                    $childPropertyDetail->unit = $unitToCovert;
                                    $childPropertyDetail->area_in_sqm = $childPlotAreaInSqmSplittedPropertyDetails;
                                    $childPropertyDetail->plot_value = $childPropertyPlotValueSplittedProperty;
                                    $childPropertyDetail->plot_value_cr = $childPropertyPlotValueCrSplittedProperty;
                                    Log::info('Child Property Before Updated:', ['child_property_id' => $childPropertyDetail->id, 'property_master_id' => $propertyId, 'unit' => $childPropertyDetail->unit, 'area_in_sqm' => $childPropertyDetail->area_in_sqm, 'plot_value' => $childPropertyDetail->plot_value, 'plot_value_cr' => $childPropertyDetail->plot_value_cr]);
                                    echo "Child Property Before Updated: child_property_id = " . $childPropertyDetail->id . ", property_master_id = " . $propertyId . ", unit = " . $childPropertyDetail->unit . ", area_in_sqm = " . $childPropertyDetail->area_in_sqm . ", plot_value = " . $childPropertyDetail->plot_value . ", plot_value_cr = " . $childPropertyDetail->plot_value_cr . "<br>";
                                    if ($childPropertyDetail->save()) {
                                        Log::info('Child Property After Updated:', ['child_property_id' => $childPropertyDetail->id, 'property_master_id' => $propertyId, 'unit' => $unitToCovert, 'area_in_sqm' => $childPlotAreaInSqmSplittedPropertyDetails, 'plot_value' => $childPropertyPlotValueSplittedProperty, 'plot_value_cr' => $childPropertyPlotValueCrSplittedProperty]);
                                        echo "Child Property After Updated: child_property_id = " . $childPropertyDetail->id . ", property_master_id = " . $propertyId . ", unit = " . $unitToCovert . ", area_in_sqm = " . $childPlotAreaInSqmSplittedPropertyDetails . ", plot_value = " . $childPropertyPlotValueSplittedProperty . ", plot_value_cr = " . $childPropertyPlotValueCrSplittedProperty . "<br>";
                                    } else {
                                        echo "Child Property ID:- " . $childPropertyId . " <b>not Updated ----------------------**********<b><br>";
                                    }
                                }
                            }
                        }
                    } else {
                        echo "Parent Property ID:- " . $propertyId . " <b>not Updated ----------------------**********<b><br>";
                    }
                }
            }
        }
    }
    /*Writing this function to update the current lessees of leased properties where conversion record got entered as blank and current lessee as blank 
    as blank string --Amita Srivastava & Sourav [09-01-2025]*/
    public function updateCurrentLesseeOfLeasedProperty(){

        $properties = PropertyMaster::where('status', 951)->get();
        foreach ($properties as $property) {
            $propertyId = $property->id;
                $isConversionRecordAvailable = PropertyTransferredLesseeDetail::where('property_master_id',$propertyId)->where('process_of_transfer','Conversion')->where('lessee_name', NULL)->get();
                if(count($isConversionRecordAvailable) > 0){
                    foreach($isConversionRecordAvailable as $conversionRecord){
                        if($conversionRecord->delete()){
                            echo "Conversion deleted for property ID:- ".$propertyId. "<br>";
                        }
                    }
                    self::updateNewCurrentLessee($propertyId);
                }
            
        }

    }

    //for updating the current lessee in seperate table
     public function updateNewCurrentLessee($id)
     {
         try {
             $propertyId = $id;
             $properties = PropertyMaster::where('id', $propertyId)->get();
 
             if ($properties->isEmpty()) {
                 echo "No properties found for the given ID.";
             }
 
             foreach ($properties as $property) {
 
                 //if property is joint
                 if ($property->is_joint_property == 1) {
 
                     $splitedPropertyDetails = SplitedPropertyDetail::where('property_master_id', $property->id)->get();
                     foreach ($splitedPropertyDetails as $splitedPropertyDetail) {
                         // Property Details
                         $propertyMasterIdChild = $property->id;
                         $splitedPropertyDetailId = $splitedPropertyDetail->id;
                         $oldPropertyIdChild = $splitedPropertyDetail->old_property_id;
                         $propertyStatusChild = $splitedPropertyDetail->property_status;
                         $currentArea = $splitedPropertyDetail->current_area;
                         $areaInSqm = $splitedPropertyDetail->area_in_sqm;
                         $unitChild = $splitedPropertyDetail->unit;
                         $presentlyKnownAsChild = $splitedPropertyDetail->presently_known_as;
                         $createdByChild = $splitedPropertyDetail->created_by;
 
 
                         // Find lessees
                         $latestBatchIdChild = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)->where('splited_property_detail_id', $splitedPropertyDetailId)->max('batch_transfer_id');
                         // if ($latestBatchIdChild === null) {
                         //     return "No lessee details found for property ID: {$property->id} and Splitted property id {$splitedPropertyDetailId}";
                         // }
 
                         if ($latestBatchIdChild) {
 
                             $lesseesWithLatestBatchIdChild = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)
                                 ->where('splited_property_detail_id', $splitedPropertyDetailId)
                                 ->where('batch_transfer_id', $latestBatchIdChild)
                                 ->pluck('lessee_name')
                                 ->toArray();
 
                             $lesseesNamesChild = implode(",", $lesseesWithLatestBatchIdChild);
                             $currentLesseeDetailChild = CurrentLesseeDetail::where('property_master_id', $property->id)->where('splited_property_detail_id', $splitedPropertyDetail->id)->first();
                             // If current lessee details already saved
                             if (!empty($currentLesseeDetailChild)) {
                                 if ($currentLesseeDetailChild['lessees_name'] != $lesseesNamesChild) {
                                     $currentLesseeDetailChild->property_status = $propertyStatusChild;
                                     $currentLesseeDetailChild->lessees_name = $lesseesNamesChild;
                                     $currentLesseeDetailChild->property_known_as = $presentlyKnownAsChild;
                                     $currentLesseeDetailChild->area = $currentArea;
                                     $currentLesseeDetailChild->unit = $unitChild;
                                     $currentLesseeDetailChild->area_in_sqm = $areaInSqm;
                                     $currentLesseeDetailChild->save();
                                 }
                             } else {
                                 CurrentLesseeDetail::create([
                                     'property_master_id' => $propertyMasterIdChild,
                                     'splited_property_detail_id' => $splitedPropertyDetailId,
                                     'old_property_id' => $oldPropertyIdChild,
                                     'property_status' => $propertyStatusChild,
                                     'lessees_name' => $lesseesNamesChild,
                                     'property_known_as' => $presentlyKnownAsChild,
                                     'area' => $currentArea,
                                     'unit' => $unitChild,
                                     'area_in_sqm' => $areaInSqm,
                                     'created_by' => $createdByChild
                                 ]);
                             }
                         }
                     }
                 } else {
 
                     // Property Details
                     $propertyMasterId = $property->id;
                     $oldPropertyId = $property->old_propert_id;
                     $propertyStatus = $property->status;
 
                     // Find lessees
                     $latestBatchId = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)->max('batch_transfer_id');
                     if ($latestBatchId === null) {
                         echo "No lessee details found for property ID: {$property->id}";
                     }
 
                     $lesseesWithLatestBatchId = PropertyTransferredLesseeDetail::where('property_master_id', $property->id)
                         ->where('batch_transfer_id', $latestBatchId)
                         ->pluck('lessee_name')
                         ->toArray();
 
                     $lesseesNames = implode(",", $lesseesWithLatestBatchId);
 
                     // Details from lease details
                     $leaseDetails = PropertyLeaseDetail::where('property_master_id', $property->id)->first();
                     if ($leaseDetails === null) {
                         echo "No lease details found for property ID: {$property->id}";
                     }
 
                     $presentlyKnown = $leaseDetails['presently_known_as'] ?? '';
                     $plotArea = $leaseDetails['plot_area'] ?? 0;
                     $unit = $leaseDetails['unit'] ?? '';
                     $plotAreaInSqm = round($leaseDetails['plot_area_in_sqm'] ?? 0, 2);
                     $createdBy = $leaseDetails['created_by'] ?? '';
 
                     $currentLesseeDetail = CurrentLesseeDetail::where('property_master_id', $property->id)->first();
 
                     // If current lessee details already saved
                     if (!empty($currentLesseeDetail)) {
                         if ($currentLesseeDetail['lessees_name'] != $lesseesNames) {
                             $currentLesseeDetail->property_status = $propertyStatus;
                             $currentLesseeDetail->lessees_name = $lesseesNames;
                             $currentLesseeDetail->property_known_as = $presentlyKnown;
                             $currentLesseeDetail->area = $plotArea;
                             $currentLesseeDetail->unit = $unit;
                             $currentLesseeDetail->area_in_sqm = $plotAreaInSqm;
                             $currentLesseeDetail->save();
                         }
                     } else {
                         CurrentLesseeDetail::create([
                             'property_master_id' => $propertyMasterId,
                             'splited_property_detail_id' => null,
                             'old_property_id' => $oldPropertyId,
                             'property_status' => $propertyStatus,
                             'lessees_name' => $lesseesNames,
                             'property_known_as' => $presentlyKnown,
                             'area' => $plotArea,
                             'unit' => $unit,
                             'area_in_sqm' => $plotAreaInSqm,
                             'created_by' => $createdBy
                         ]);
                     }
                 }
             }
 
             echo "Current lessee details updated successfully.";
         } catch (\Exception $e) {
             echo "An error occurred: " . $e->getMessage();
         }
     }
}
