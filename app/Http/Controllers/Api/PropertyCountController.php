<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PropertyCountController extends Controller
{
    // Function for property count api for website Swati Mishra 20-01-2025
    public function propertyCountSummary()
    {
        // Query for Total Property Count
        $totalPropertyCountQuery = "SELECT SUM(property_count) AS total_property_count
            FROM (
                SELECT COUNT(*) AS property_count
                FROM splited_property_details spd
                JOIN property_masters pm ON spd.property_master_id = pm.id
                WHERE pm.is_joint_property = 1

                UNION ALL

                SELECT COUNT(*) AS property_count
                FROM property_masters
                WHERE is_joint_property IS NULL
            ) AS counts";

        // Query for Property Type Counts
        $propertyTypeCountsQuery = "SELECT 
                COALESCE(case1.residential_properties_count, 0) + COALESCE(case2.residential_properties_count, 0) AS residential_count,
                COALESCE(case1.commercial_properties_count, 0) + COALESCE(case2.commercial_properties_count, 0) AS commercial_count,
                COALESCE(case1.institutional_properties_count, 0) + COALESCE(case2.institutional_properties_count, 0) AS institutional_count,
                COALESCE(case1.industrial_properties_count, 0) + COALESCE(case2.industrial_properties_count, 0) AS industrial_count
            FROM 
                (
                    SELECT  
                        COUNT(property_type = 47 OR NULL) AS residential_properties_count,
                        COUNT(property_type = 48 OR NULL) AS commercial_properties_count,
                        COUNT(property_type = 49 OR NULL) AS institutional_properties_count,
                        COUNT(property_type = 469 OR NULL) AS industrial_properties_count
                    FROM 
                        property_masters
                    WHERE 
                        is_joint_property IS NULL 
                        AND status != 1476
                ) AS case1,
                (
                    SELECT  
                        COUNT(property_type = 47 OR NULL) AS residential_properties_count,
                        COUNT(property_type = 48 OR NULL) AS commercial_properties_count,
                        COUNT(property_type = 49 OR NULL) AS institutional_properties_count,
                        COUNT(property_type = 469 OR NULL) AS industrial_properties_count
                    FROM 
                        (
                            SELECT  
                                pm.property_type
                            FROM 
                                splited_property_details spd
                            JOIN 
                                property_masters pm 
                            ON 
                                spd.property_master_id = pm.id
                            WHERE 
                                pm.is_joint_property = 1
                        ) AS joint_properties
                ) AS case2";

        // Query for Property Sub-Type Counts
        $propertySubTypeCountsQuery = "SELECT 
                COALESCE(case1.government_property_count, 0) + COALESCE(case2.government_property_count, 0) AS government_count,
                COALESCE(case1.foreign_mission_count, 0) + COALESCE(case2.foreign_mission_count, 0) AS foreign_mission_count
            FROM 
                (
                    SELECT  
                        COUNT(property_type = 49 AND property_sub_type IN (1359, 1360) OR NULL) AS government_property_count,
                        COUNT(property_type = 49 AND property_sub_type = 1361 OR NULL) AS foreign_mission_count
                    FROM 
                        property_masters
                    WHERE 
                        is_joint_property IS NULL 
                        AND status != 1476
                ) AS case1,
                (
                    SELECT  
                        COUNT(property_type = 49 AND property_sub_type IN (1359, 1360) OR NULL) AS government_property_count,
                        COUNT(property_type = 49 AND property_sub_type = 1361 OR NULL) AS foreign_mission_count
                    FROM 
                        (
                            SELECT  
                                pm.property_type,
                                pm.property_sub_type
                            FROM 
                                splited_property_details spd
                            JOIN 
                                property_masters pm 
                            ON 
                                spd.property_master_id = pm.id
                            WHERE 
                                pm.is_joint_property = 1
                        ) AS joint_properties
                ) AS case2";

        $totalPropertyCountResult = DB::select($totalPropertyCountQuery);
        $propertyTypeCountsResult = DB::select($propertyTypeCountsQuery);
        $propertySubTypeCountsResult = DB::select($propertySubTypeCountsQuery);

        $totalPropertyCount = $totalPropertyCountResult[0]->total_property_count ?? 0;

        if ($totalPropertyCount > 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Property summary fetched successfully',
                'total_property_count' => $totalPropertyCount,
                'property_type_counts' => $propertyTypeCountsResult[0] ?? null,
                'property_sub_type_counts' => $propertySubTypeCountsResult[0] ?? null,
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'No properties found',
            'total_property_count' => 0,
            'property_type_counts' => null,
            'property_sub_type_counts' => null,
        ], 404);
    }

}
