<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Services\MisService;
use App\Services\ColonyService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailExport;
use App\Jobs\CustomizedReportExport;
use App\Jobs\DetailedReportExport;
use App\Jobs\ReportExport as JobsReportExport;
use App\Models\PropertyMaster;
use App\Models\Item;
//use App\Models\User;
use App\Models\OldColony;
use App\Models\CurrentLesseeDetail;
//use App\Models\PropertyRevivisedGroundRent;
use App\Models\UnallottedPropertyDetail;
use App\Models\PropertyTransferredLesseeDetail;
use App\Models\SplitedPropertyDetail;
use App\Models\PropertyLeaseDetail;
use App\Models\PropertySectionMapping;
use Auth;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:view reports', ['only' => ['index', 'tabularRecord']]);
    }

    public function index(ReportService $reportService, MisService $misService, ColonyService $colonyService)
    {
        $data['landTypes'] = $misService->getItemsByGroupId(1051);
        $data['propertyTypes'] = $misService->getItemsByGroupId(1052);
        $data['leaseTypes'] = $misService->getItemsByGroupId(102);
        $data['propertyStatus'] = $misService->getItemsByGroupId(109);
        // $data['results'] = $reportService->filterResults();
        $data['results'] = [];
        // $data['colonyList'] = $colonyService->getAllColonies();//changed by swati as mis colony name data needs to be fetched with new name
        $data['colonyList'] = $colonyService->misDoneForColonies();
        return view('report.report', $data);
    }


    public function getPropertyResults(Request $request, ReportService $reportService)
    {
        $filters = $request->filters;
        $results = $reportService->filterResults($filters);
        return response()->json($results);
    }

    public function tabularRecord(ReportService $reportService)
    {
        $tabularRecord = $reportService->tabularRecord();
        return view('tabular_record', compact(['tabularRecord']));
    }

    public function getDistinctSubTypes(Request $request, ReportService $reportService)
    {
        $types = $request->types;
        $subtypes = $reportService->getDistinctSubTypes($types);
        return response()->json($subtypes);
    }
    /* public function reportExport(Request $request, ReportService $reportService)
    {
        $format = $request->format;
        $filters = $request->filters ?? [];
        $results = $reportService->filterResults($filters, false);
        /*$chunkSize = 10000; // Number of rows per chunk
        $chunks = array_chunk($results, $chunkSize); // Split data into chunks
        $export = new ReportExport($chunks); /
        $rows = []; //header rows
        foreach ($results as $index => $item) {

            $rows[] = [
                'old_propert_id' => $item->old_propert_id,
                'unique_propert_id' => $item->unique_propert_id,
                'land_type' => $item->land_type,
                'status' => $item->status,
                'lease_tenure' => $item->lease_tenure,
                'land_use' => $item->land_use,
                'area' => $item->area_in_sqm,
                'address' => $item->address,
                'lesse_name' => $item->lesse_name,
                'gr_in_re_rs' => $item->gr_in_re_rs,
                'gr' => $item->gr,
            ];
        };
        $export = new ReportExport([
            $rows

        ]);
        /* if ($format == 'csv') {
            return Excel::download($export, 'report.csv', \Maatwebsite\Excel\Excel::CSV, [
                'Content-Type' => 'text/csv',
            ]);
        } /
        if ($format == 'xls') {
            return Excel::download($export, 'report.xls', \Maatwebsite\Excel\Excel::XLS);
        }
        /* if ($format == 'pdf') {
            return Excel::download($export, 'report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        } *
    } */
    public function reportExport(Request $request)
    {
        $filters = $request->filters ?? [];

        //$email = Auth::user()->email;
        $email = 'nitinrag@gmail.com';

        dispatch(new JobsReportExport($filters, $email));

        return response()->json(['status' => 'Creating export. You will recieve the email when export is ready']);
        /* $fileName = 'public/exports/' . now()->timestamp . '.xls';
        $page = 1;
        $service = new ReportService();
        $rows = [];
        $chunkSize = 1000;
        $filters['limit'] = $chunkSize;
        $results = $service->filterResults($filters)['rows'];
        do {
            $results = $service->filterResults($filters)['rows'];
            $page++;
            if (is_null($results)) break;

            foreach ($results as $index => $item) {
                // dd($chunk);
                // foreach ($chunk as $item) {
                $rows[] = [
                    'old_propert_id' => $item->old_propert_id,
                    'unique_propert_id' => $item->unique_propert_id,
                    'land_type' => $item->land_type,
                    'status' => $item->status,
                    'lease_tenure' => $item->lease_tenure,
                    'land_use' => $item->land_use,
                    'area' => $item->area_in_sqm,
                    'address' => $item->address,
                    'lesse_name' => $item->lesse_name,
                    'gr_in_re_rs' => $item->gr_in_re_rs,
                    'gr' => $item->gr,
                ];
                // }
            }
            // If you want to write each chunk to the file progressively
            // You can do so here depending on the format.

        } while ($page < 3); //while (count($results) == $chunkSize);
        $export = new ReportExport(collect($rows));
        Excel::store($export, $fileName, 'local'); */
    }


    //For exporting the property details START ***************************************************************************
    //Sourav Chauhan - 4/June/2024
    /*public function detailsExport(Request $request, ReportService $reportService)
    {
        try {
            $format = $request->format;
            $rows = []; //header rows
            $filters = $request->filters ?? [];


            $loginUser = Auth::user();

            if (isset($filters['colonyNameOld'])) {
                // dd("inside if");
                if ($loginUser->can('view.all.details')) {
                    $propertyDetails = PropertyMaster::query()
                        ->when($filters['colonyNameOld'], function ($q) use ($filters) {
                            $q->where('new_colony_name', $filters['colonyNameOld']);
                        })->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user'])
                        ->get();

                } else {
                    $propertyDetails = PropertyMaster::query()
                        ->when($filters['colonyNameOld'], function ($q) use ($filters) {
                            $q->where('new_colony_name', $filters['colonyNameOld']);
                        })->where('created_by', $loginUser->id)
                        ->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user'])
                        ->get();

                }

            } else {
                if (isset($filters['propId']) || isset($filters['date'])) {
                    // $propertyDetails = PropertyMaster::where('old_propert_id', 'like', '%'.$filters['propId'].'%')
                    // ->get();
                    if (isset($filters['date'])) {
                        // $dateTime = new \DateTime($filters['date']);
                        // $dateTime2 = new \DateTime($filters['dateEnd']);
                        // $formattedDate = $dateTime->format('d-m-Y');//production
                        $formattedDate = $filters['date'];//development
                        if (isset($filters['dateEnd'])) {

                            $formattedDateEnd = $filters['dateEnd'];//development
                        }
                        if ($loginUser->can('view.all.details')) {
                            if (isset($filters['dateEnd']) && isset($filters['date'])) {
                                $propertyDetails = PropertyMaster::query()
                                ->when(isset($formattedDate) && isset($formattedDateEnd), function ($q) use ($formattedDate, $formattedDateEnd) {
                                    $q->whereDate('created_at', '>=', $formattedDate)
                                      ->whereDate('created_at', '<=', $formattedDateEnd);
                                })->get();
                            
                            } else if (isset($filters['date'])) {
                                $propertyDetails = PropertyMaster::query()                          
                                ->when($filters['date'], function ($q) use ($formattedDate) {
                                    $q->where('created_at', 'like', '%' . $formattedDate . '%');
                                })->get();
                            }

                        } else {
                            if (isset($filters['dateEnd']) && isset($filters['date'])) {
                                $propertyDetails = PropertyMaster::query()
                                ->when(isset($formattedDate) && isset($formattedDateEnd), function ($q) use ($formattedDate, $formattedDateEnd) {
                                    $q->whereDate('created_at', '>=', $formattedDate)
                                      ->whereDate('created_at', '<=', $formattedDateEnd);
                                })->where('created_by', $loginUser->id)->get();
                            
                            } else if (isset($filters['date'])) {
                                $propertyDetails = PropertyMaster::query()                          
                                ->when($filters['date'], function ($q) use ($formattedDate) {
                                    $q->where('created_at', 'like', '%' . $formattedDate . '%');
                                })->where('created_by', $loginUser->id)->get();
                            }
                        }
                        // dd($propertyDetails);
                    } else {

                        if ($loginUser->can('view.all.details')) {
                            $propertyDetails = PropertyMaster::query()
                                ->when($filters['propId'], function ($q) use ($filters) {
                                    $q->where('old_propert_id', 'like', '%' . $filters['propId'] . '%')
                                        ->orWhere('unique_propert_id', 'like', '%' . $filters['propId'] . '%');
                                })->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user'])
                                ->get();

                        } else {
                            $propertyDetails = PropertyMaster::query()
                                ->when($filters['propId'], function ($q) use ($filters) {
                                    $q->where('old_propert_id', 'like', '%' . $filters['propId'] . '%')
                                        ->orWhere('unique_propert_id', 'like', '%' . $filters['propId'] . '%');
                                })->where('created_by', $loginUser->id)
                                ->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user'])
                                ->get();
                        }

                    }

                } else {
                    if ($loginUser->can('view.all.details')) {
                        $propertyDetails = PropertyMaster::with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user'])->get();
                    } else {
                        $propertyDetails = PropertyMaster::where('created_by', $loginUser->id)->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user'])->get();
                    }
                }
            }
            // dd($propertyDetails);

            foreach ($propertyDetails as $propertyDetail) {
                // dd($propertyDetail);
                $name = '';
                $colconyName = '';
                $name = $propertyDetail->user->name;
                if(isset($filters['colonyNameOld'])){
                    $colconyName = OldColony::where('id', $filters['colonyNameOld'])->first();
                } else {
                    $colconyName = OldColony::where('id', $propertyDetail->new_colony_name)->first();
                }

                // $data = Item::whereIn('id', [$propertyDetail->property_type, $propertyDetail->property_sub_type, $propertyDetail->status, $propertyDetail->propertyLeaseDetail->unit])->get();
                $propertyType = Item::where('id', $propertyDetail->property_type)->first();
                $propertySubType = Item::where('id', $propertyDetail->property_sub_type)->first();
                $status = Item::where('id', $propertyDetail->status)->first();
                if(isset($propertyDetail->propertyLeaseDetail)){

                    $unit = Item::where('id', $propertyDetail->propertyLeaseDetail->unit)->first();
                } else {
                    $unit = '';
                }

                // Convert UTC time to IST using Carbon
                $utcTime = $propertyDetail->created_at; // Assuming $propertyDetail contains your UTC timestamp
                $istTime = $utcTime->setTimezone('Asia/Kolkata');

                //Latest Lessee
                $allLessees = $propertyDetail->propertyTransferredLesseeDetails;
                $lengthOfLessees = count($allLessees);
                $latestLessee = $allLessees[$lengthOfLessees - 1];

                //address
                $blockNo = isset($propertyDetail->block_no) ? $propertyDetail->block_no . '/' : '';
                $plotOrPropertyNo = isset( $propertyDetail->plot_or_property_no) ? $propertyDetail->plot_or_property_no . '/' : '';
                $colconyName = $colconyName->name ?? '';

                $rows[] = [
                    'Property Id' => $propertyDetail->unique_propert_id ?? '',
                    'Old Property Id' => $propertyDetail->old_propert_id ?? '',
                    'File Number' => $propertyDetail->unique_file_no ?? '',
                    'Old File Number' => $propertyDetail->file_no ?? '',
                    'Property Type' => isset($propertyDetail->property_type) ? isset($propertyType) ? $propertyType['item_name'] : '' : '',
                    'Property SubType' => isset($propertyDetail->property_sub_type) ? isset($propertySubType) ? $propertySubType['item_name'] : '' : '',
                    'Property Status' => isset($propertyDetail->status) ? isset($status) ? $status['item_name'] : '' : '',
                    'Section' => $propertyDetail->section_code ?? '',
                    'Address' => $blockNo . $plotOrPropertyNo . $colconyName,
                    'Premium (₹)' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->premium . '.' . $propertyDetail->propertyLeaseDetail->premium_in_paisa ?? $propertyDetail->propertyLeaseDetail->premium_in_aana : '',
                    'Ground Rent (₹)' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->gr_in_re_rs . '.' . $propertyDetail->propertyLeaseDetail->gr_in_paisa ?? $propertyDetail->propertyLeaseDetail->gr_in_aana : '',
                    'Area' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->plot_area . ' ' . (isset($unit) ? $unit['item_name'] : '') : '',
                    'Area in Sqm' => $propertyDetail->propertyLeaseDetail->plot_area_in_sqm ?? '',
                    'Block' => $propertyDetail->block_no ?? '',
                    'Plot' => $propertyDetail->plot_or_property_no ?? '',
                    'Colony' => $colconyName->name ?? '',
                    'Date Of Expiration' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->date_of_expiration : '',
                    'Date Of Execution' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->doe : '',
                    'Date Of Allotment' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->doa : '',
                    'Presently Known As' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->presently_known_as : '',
                    'Start Date Of GR' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->start_date_of_gr : '',
                    'RGR Duration' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->rgr_duration : '',
                    'First RGR Due On' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->first_rgr_due_on : '',
                    'Last Inspection Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_inspection_ir_date : '',
                    'Last Demand Letter Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_letter_date : '',
                    'Last Demand Id' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_id : '',
                    'Last Demand Amount' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_amount : '',
                    'Last Amount Received' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_amount_received : '',
                    'Last Amount Received Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_amount_received_date : '',
                    'Total Dues' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->total_dues : '',
                    'Latest Lessee Name' => $latestLessee['lessee_name'] ?? '',
                    'Lessee Address' => $propertyDetail->propertyContactDetail->address ?? '',
                    'Lessee Phone' => $propertyDetail->propertyContactDetail->phone_no ?? '',
                    'Lessee Email' => $propertyDetail->propertyContactDetail->email ?? '',
                    'Entry By' => $name,
                    'Entry At' => $istTime->format('Y-m-d H:i:s')
                ];
            }

            // dd($rows);

            $export = new DetailExport([
                $rows

            ]);

            if ($format == 'csv') {
                return Excel::download($export, 'details.csv', \Maatwebsite\Excel\Excel::CSV, [
                    'Content-Type' => 'text/csv',
                ]);
            }
            if ($format == 'xls') {
                return Excel::download($export, 'report.xls', \Maatwebsite\Excel\Excel::XLS);
            }
            if ($format == 'pdf') {
                return Excel::download($export, 'report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
            }
        } catch (\Exception $e) {
            dd($e);
            // return redirect()->back()->with('failure', $e->getMessage());
        }
    }*/
    //For exporting the property details END ***************************************************************************

    /*public function detailsExport(Request $request, ReportService $reportService)
    {
        try {
            $format = $request->format;
            $filters = $request->filters ?? [];
            $loginUser = Auth::user();

            // Determine the query to use based on the filters and user permissions
            $query = PropertyMaster::query()
                ->when(isset($filters['colonyNameOld']), function ($q) use ($filters, $loginUser) {
                    $q->where('new_colony_name', $filters['colonyNameOld']);
                    if (!$loginUser->can('view.all.details')) {
                        $q->where('created_by', $loginUser->id);
                    }
                })
                ->when(isset($filters['date']), function ($q) use ($filters, $loginUser) {
                    $formattedDate = $filters['date'];
                    $q->where('created_at', 'like', '%' . $formattedDate . '%');
                    if (!$loginUser->can('view.all.details')) {
                        $q->where('created_by', $loginUser->id);
                    }
                })
                ->when(isset($filters['propId']), function ($q) use ($filters, $loginUser) {
                    $q->where(function ($query) use ($filters) {
                        $query->where('old_propert_id', 'like', '%' . $filters['propId'] . '%')
                            ->orWhere('unique_propert_id', 'like', '%' . $filters['propId'] . '%');
                    });
                    if (!$loginUser->can('view.all.details')) {
                        $q->where('created_by', $loginUser->id);
                    }
                });

            // Add eager loading
            $query->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user']);

            $rows = []; // Initialize rows array

            // Chunk the results to handle large datasets
            $query->chunk(5000, function ($propertyDetails) use (&$rows, $filters) {
                foreach ($propertyDetails as $propertyDetail) {
                    $name = '';
                    $colonyName = '';
                    $propertyType = $propertySubType = $status = $unit = '';
                    $name = $propertyDetail->user->name;
                    // if (isset($filters['colonyNameOld'])) {
                    //     $colonyName = OldColony::where('id', $filters['colonyNameOld'])->first();
                    // } else {
                    //     $colonyName = OldColony::where('id', $propertyDetail->new_colony_name)->first();
                    // }
                    // if (!empty($propertyDetail->property_type)) {
                    //     $propertyType = Item::where('id', $propertyDetail->property_type)->first();
                    // }
                    // if (!empty($propertyDetail->property_sub_type)) {
                    //     $propertySubType = Item::where('id', $propertyDetail->property_sub_type)->first();
                    // }
                    // if (!empty($propertyDetail->status)) {
                    //     $status = Item::where('id', $propertyDetail->status)->first();
                    // }
                    // if (!empty($propertyDetail->propertyLeaseDetail->unit)) {
                    //     $unit = Item::where('id', $propertyDetail->propertyLeaseDetail->unit)->first();
                    // }

                    // Convert UTC time to IST using Carbon
                    $utcTime = $propertyDetail->created_at; // Assuming $propertyDetail contains your UTC timestamp
                    $istTime = $utcTime->setTimezone('Asia/Kolkata');

                    // Latest Lessee
                    $allLessees = $propertyDetail->propertyTransferredLesseeDetails;
                    $latestLessee = $allLessees->isNotEmpty() ? $allLessees->last() : null;

                    $rows[] = [
                        'Property Id' => $propertyDetail->unique_propert_id ?? '',
                        'Old Property Id' => $propertyDetail->old_propert_id ?? '',
                        'File Number' => $propertyDetail->unique_file_no ?? '',
                        'Old File Number' => $propertyDetail->file_no ?? '',
                        'Property Type' => isset($propertyDetail->property_type) ? (isset($propertyType) ? '' : '') : '',
                        'Property SubType' => isset($propertyDetail->property_sub_type) ? (isset($propertySubType) ? '' : '') : '',
                        'Property Status' => isset($propertyDetail->status) ? (isset($status) ? '' : '') : '',
                        'Section' => $propertyDetail->section_code ?? '',
                        'Address' => $propertyDetail->block_no . '/' . $propertyDetail->plot_or_property_no  ?? '',
                        'Premium (₹)' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->premium . '.' . $propertyDetail->propertyLeaseDetail->premium_in_paisa ?? $propertyDetail->propertyLeaseDetail->premium_in_aana : '',
                        'Ground Rent (₹)' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->gr_in_re_rs . '.' . $propertyDetail->propertyLeaseDetail->gr_in_paisa ?? $propertyDetail->propertyLeaseDetail->gr_in_aana : '',
                        'Area' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->plot_area . ' ' . (isset($unit) ? '' : '') : '',
                        'Area in Sqm' => $propertyDetail->propertyLeaseDetail->plot_area_in_sqm ?? '',
                        'Block' => $propertyDetail->block_no ?? '',
                        'Plot' => $propertyDetail->plot_or_property_no ?? '',
                        'Colony' => '',
                        'Date Of Expiration' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->date_of_expiration : '',
                        'Date Of Execution' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->doe : '',
                        'Date Of Allotment' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->doa : '',
                        'Presently Known As' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->presently_known_as : '',
                        'Start Date Of GR' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->start_date_of_gr : '',
                        'RGR Duration' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->rgr_duration : '',
                        'First RGR Due On' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->first_rgr_due_on : '',
                        'Last Inspection Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_inspection_ir_date : '',
                        'Last Demand Letter Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_letter_date : '',
                        'Last Demand Id' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_id : '',
                        'Last Demand Amount' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_amount : '',
                        'Last Amount Received' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_amount_received : '',
                        'Last Amount Received Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_amount_received_date : '',
                        'Total Dues' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->total_dues : '',
                        'Latest Lessee Name' => $latestLessee ? $latestLessee['lessee_name'] : '',
                        'Lessee Address' => $propertyDetail->propertyContactDetail->address ?? '',
                        'Lessee Phone' => $propertyDetail->propertyContactDetail->phone_no ?? '',
                        'Lessee Email' => $propertyDetail->propertyContactDetail->email ?? '',
                        'Entry By' => $name,
                        'Entry At' => $istTime->format('Y-m-d H:i:s')
                    ];
                }
            });

            $export = new DetailExport([
                $rows
            ]);

            if ($format == 'csv') {
                return Excel::download($export, 'details.csv', \Maatwebsite\Excel\Excel::CSV, [
                    'Content-Type' => 'text/csv',
                ]);
            }
            if ($format == 'xls') {
                return Excel::download($export, 'report.xls', \Maatwebsite\Excel\Excel::XLS);
            }
            if ($format == 'pdf') {
                return Excel::download($export, 'report.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
            }
        } catch (\Exception $e) {
            dd($e);
            // return redirect()->back()->with('failure', $e->getMessage());
        }
    }*/

    public function detailsExport(Request $request)
    {
        try {
            $format = $request->format;
            $filters = $request->filters ?? [];
            $loginUser = Auth::user();

            // Determine the query to use based on the filters and user permissions
            $query = PropertyMaster::query()
                ->when(isset($filters['colonyNameOld']), function ($q) use ($filters, $loginUser) {
                    $q->where('new_colony_name', $filters['colonyNameOld']);
                    if (!$loginUser->can('view.all.details')) {
                        $q->where('created_by', $loginUser->id);
                    }
                })
                ->when(isset($filters['date']), function ($q) use ($filters, $loginUser) {
                    $formattedDate = $filters['date'];
                    $q->where('created_at', 'like', '%' . $formattedDate . '%');
                    if (!$loginUser->can('view.all.details')) {
                        $q->where('created_by', $loginUser->id);
                    }
                })
                ->when(isset($filters['propId']), function ($q) use ($filters, $loginUser) {
                    $q->where(function ($query) use ($filters) {
                        $query->where('old_propert_id', 'like', '%' . $filters['propId'] . '%')
                            ->orWhere('unique_propert_id', 'like', '%' . $filters['propId'] . '%');
                    });
                    if (!$loginUser->can('view.all.details')) {
                        $q->where('created_by', $loginUser->id);
                    }
                    if($loginUser->roles[0]->id == 7 || $loginUser->roles[0]->id == 8 || $loginUser->roles[0]->id == 9 || $loginUser->roles[0]->id == 10){
                        $loginUserSections = $loginUser->sections;
                        $allSections = [];
                        $allSectionIds = [];
                        $allTypes = [];
                        $allSubTypes = [];
                        foreach($loginUserSections as $loginUserSection){
                            $sectionCode = $loginUserSection->section_code;
                            $allSections[] = $sectionCode;
                            $sectionId = $loginUserSection->id;
                            $allSectionIds[] = $sectionId;
                        }
                        $propertySectionMappings = PropertySectionMapping::whereIn('section_id',$allSectionIds)->get();
                        foreach($propertySectionMappings as $propertySectionMapping){
                            $type = $propertySectionMapping->property_type;
                            $allTypes[] = $type;
                            $subType = $propertySectionMapping->property_subtype;
                            $allSubTypes[] = $subType;
                        }
                        $q->whereIn('section_code',$allSections)->whereIn('property_type',$allTypes)->whereIn('property_sub_type',$allSubTypes);
                    }
                });
                // ->where('file_no', 'like', 'L-IIA%');
                // ->orWhere('file_no', 'like', 'L-IIB%')
                // ->orWhere('file_no', 'like', 'L-III%')
                // ->orWhere('file_no', 'like', 'L-IV%')
                // ->orWhere('file_no', 'like', 'L-V%')

            // Add eager loading
            $query->with(['propertyLeaseDetail', 'propertyTransferredLesseeDetails', 'propertyInspectionDemandDetail', 'propertyMiscDetail', 'propertyContactDetail', 'splitedPropertyDetail', 'oldColony', 'user']);
            $rows = []; // Initialize rows array

            // Chunk the results to handle large datasets
            $query->chunk(1000, function ($propertyDetails) use (&$rows, $filters) {
                foreach ($propertyDetails as $propertyDetail) {
                    $name = '';
                    $colonyName = '';
                    $propertyType = $propertySubType = $propertyTypeNew = $propertySubTypeNew = $status = $unit = '';
                    $name = $propertyDetail->user ? $propertyDetail->user->name: '';
                    if (isset($filters['colonyNameOld'])) {
                        $colonyName = OldColony::where('id', $filters['colonyNameOld'])->first();
                    } else {
                        $colonyName = OldColony::where('id', $propertyDetail->new_colony_name)->first();
                    }


                    if (!empty($propertyDetail->propertyLeaseDetail)) {
                        $propertyTypeData = Item::where('id', $propertyDetail->propertyLeaseDetail->property_type_as_per_lease)->first();
                        $propertyType = $propertyTypeData['item_name'];

                        $propertySubTypeData = Item::where('id', $propertyDetail->propertyLeaseDetail->property_sub_type_as_per_lease)->first();
                        $propertySubType = $propertySubTypeData['item_name'];
                        if (!empty($propertyDetail->propertyLeaseDetail->property_type_at_present)) {
                            $propertyTypeNewData = Item::where('id', $propertyDetail->propertyLeaseDetail->property_type_at_present)->first();
                            $propertyTypeNew = $propertyTypeNewData['item_name'];

                            $propertySubTypeNewData = Item::where('id', $propertyDetail->propertyLeaseDetail->property_sub_type_at_present)->first();
                            $propertySubTypeNew = $propertySubTypeNewData['item_name'];
                        }
                    }


                    // if (!empty($propertyDetail->property_sub_type)) {
                    //     $propertySubType = Item::where('id', $propertyDetail->property_sub_type)->first();
                    // }
                    if (!empty($propertyDetail->status)) {
                        $status = Item::where('id', $propertyDetail->status)->first();
                    }
                    if (!empty($propertyDetail->propertyLeaseDetail->unit)) {
                        $unit = Item::where('id', $propertyDetail->propertyLeaseDetail->unit)->first();
                    }
                    if (!empty($propertyDetail->propertyLeaseDetail->unit)) {
                        $typeOfLease = Item::where('id', $propertyDetail->propertyLeaseDetail->type_of_lease)->first();
                    }
                    if (!empty($propertyDetail->land_type)) {
                        $landType = Item::where('id', $propertyDetail->land_type)->first();
                    }

                    // Convert UTC time to IST using Carbon
                    $utcTime = $propertyDetail->created_at; // Assuming $propertyDetail contains your UTC timestamp
                    $istTime = $utcTime->setTimezone('Asia/Kolkata');

                    // Latest Lessee
                    // $allLessees = $propertyDetail->propertyTransferredLesseeDetails;
                    // $latestLessee = $allLessees->isNotEmpty() ? $allLessees->last() : null;

                    //added for getting the current lesse from current lessees table - SOURAV CHAUHAN (15/July/2024)
                    $latestLessee = CurrentLesseeDetail::where('property_master_id', $propertyDetail->id)->first();


                    //Splitted properties START - SOURAV CHAUHAN (20/sep/2024)****************
                    $jointPropertiesArray = [];
                    if ($propertyDetail->splitedPropertyDetail->isEmpty()) {
                    } else {
                        foreach ($propertyDetail->splitedPropertyDetail as $key => $chldProperty) {
                            if (!empty($chldProperty->old_property_id)) {
                                $jointPropertiesArray[] = $chldProperty->old_property_id;
                            }
                        }
                    }
                    $jointProperties = implode('/', $jointPropertiesArray);
                    //Splitted properties END - SOURAV CHAUHAN (20/sep/2024)****************

                    $rows[] = [
                        'Property Id' => $propertyDetail->unique_propert_id ?? '',
                        'Old Property Id' => $propertyDetail->old_propert_id ?? '',
                        'Joint Properties' => $jointProperties ?? '',
                        'File Number' => $propertyDetail->unique_file_no ?? '',
                        'Old File Number' => $propertyDetail->file_no ?? '',
                        'Land Type' => isset($landType) ? $landType['item_name'] : '',
                        'Property Status' => isset($propertyDetail->status) ? (isset($status) ? $status['item_name'] : '') : '',
                        'Property Type' => $propertyType,
                        'Property SubType' => $propertySubType,
                        'Is Land Use Changed' => isset($propertyDetail->propertyLeaseDetail->is_land_use_changed) ? 'Yes'  : 'No',
                        'Latest Property Type' => $propertyTypeNew,
                        'Latest Property SubType' => $propertySubTypeNew,
                        'Section' => $propertyDetail->section_code ?? '',
                        'Address' => $propertyDetail->block_no . '/' . $propertyDetail->plot_or_property_no . '/' . $colonyName->name ?? '',
                        'Premium (₹)' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->premium . '.' . $propertyDetail->propertyLeaseDetail->premium_in_paisa ?? $propertyDetail->propertyLeaseDetail->premium_in_aana : '',
                        'Ground Rent (₹)' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->gr_in_re_rs . '.' . $propertyDetail->propertyLeaseDetail->gr_in_paisa ?? $propertyDetail->propertyLeaseDetail->gr_in_aana : '',
                        'Area' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->plot_area . ' ' . (isset($unit) ? $unit['item_name'] : '') : '',
                        'Area in Sqm' => $propertyDetail->propertyLeaseDetail->plot_area_in_sqm ?? '',
                        'Colony' => $colonyName->name ?? '',
                        'Block' => $propertyDetail->block_no ?? '',
                        'Plot' => $propertyDetail->plot_or_property_no ?? '',
                        'Presently Known As' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->presently_known_as : '',
                        'Lease Type' => isset($typeOfLease) ? $typeOfLease['item_name'] : '',
                        'Date Of Allotment' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->doa : '',
                        'Date Of Execution' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->doe : '',
                        'Date Of Expiration' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->date_of_expiration : '',
                        'Start Date Of GR' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->start_date_of_gr : '',
                        'RGR Duration' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->rgr_duration : '',
                        'First RGR Due On' => isset($propertyDetail->propertyLeaseDetail) ? $propertyDetail->propertyLeaseDetail->first_rgr_due_on : '',
                        'Last Inspection Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_inspection_ir_date : '',
                        'Last Demand Letter Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_letter_date : '',
                        'Last Demand Id' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_id : '',
                        'Last Demand Amount' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_demand_amount : '',
                        'Last Amount Received' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_amount_received : '',
                        'Last Amount Received Date' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->last_amount_received_date : '',
                        'Total Dues' => isset($propertyDetail->propertyInspectionDemandDetail) ? $propertyDetail->propertyInspectionDemandDetail->total_dues : '',
                        'Latest Lessee Name' => $latestLessee ? $latestLessee['lessees_name'] : '',
                        'Lessee Address' => $propertyDetail->propertyContactDetail->address ?? '',
                        'Lessee Phone' => $propertyDetail->propertyContactDetail->phone_no ?? '',
                        'Lessee Email' => $propertyDetail->propertyContactDetail->email ?? '',
                        'Entry By' => $name,
                        'Entry At' => $istTime->format('Y-m-d H:i:s')
                    ];
                }
            });

            if (!empty($rows)) {
                return (new FastExcel($rows))->download('details.csv');
            }



        } catch (\Exception $e) {
            dd($e);
            // return redirect()->back()->with('failure', $e->getMessage());
        }
    }

    public function detailedReport(Request $request, ColonyService $colonyService, MisService $misService, ReportService $reportService)
    {
        $filters = [];

        $filters = $request->except(['export']);
        if ($request->export == 1) {
            $email = Auth::user()->email;
            // $email = Auth::user()->email;
            dispatch(new DetailedReportExport($filters, $email));
            return redirect()->back()->with('success', 'Generating the report. Download link will be sent to your email id');
        }
        $properties = $reportService->detailedReport($filters);
        $subtypes = [];
        if (isset($filters['property_type'])) {
            $subtypes = $reportService->getDistinctSubTypes($filters['property_type']);
        }
        $data['colonyList'] = $colonyService->misDoneForColonies();
        $data['propertyStatus'] = $misService->getItemsByGroupId(109);
        $data['landTypes'] = $misService->getItemsByGroupId(1051);
        $data['leaseTypes'] = $misService->getItemsByGroupId(102);
        $data['propertyTypes'] = $misService->getItemsByGroupId(1052);
        $data['propertySubtypes'] = $subtypes;
        $data['properties'] = $properties;
        $data['total'] = $properties['total'];
        $data['filters'] = $filters;
        return view('report.detailed-report', $data);
    }
    public function download($file)
    {
        $fileName = base64_decode($file);
        if (Storage::exists($fileName)) {
            return Storage::download($fileName);
        } else {
            abort(404);
        }
    }

    public function customizeReport(Request $request, ColonyService $colonyService, MisService $misService, ReportService $reportService)
    {
        // dd($request->all());
        $filters = [];

        $filters = $request->except(['export']);
        // if ($request->export == 1) {
        //     $email = Auth::user()->email;
        //     // $email = Auth::user()->email;
        //     dispatch(new DetailedReportExport($filters, $email));
        //     return redirect()->back()->with('success', 'Generating the report. Download link will be sent to your email id');
        // }
        $properties = $reportService->customizedReport($filters);
        $data['properties'] = $properties;
        $data['total'] = count($properties);
        $data['filters'] = $filters;
        // dd($data);
        if($request->filter == 'CWLHFHP'){ //CWLHFHP stands for Colony wise lease hold free hold property
            return view('report.customize-report-freehold-leasehold', $data);
        } else {
            return view('report.customize-report', $data);
        }
        
    }

    public function unallotedPropertyView(Request $request)
    {
        return view('report.unalloted-properties-index');
    }

    public function getUnallotedProperties(Request $request)
    {
        $query = UnallottedPropertyDetail::query()
            ->leftJoin('property_masters', 'unallotted_property_details.property_master_id', '=', 'property_masters.id')
            ->leftJoin('items', 'property_masters.land_type', '=', 'items.id')
            ->leftJoin('old_colonies', 'property_masters.new_colony_name', '=', 'old_colonies.id')
            ->leftJoin('departments', 'unallotted_property_details.transferred_to', '=', 'departments.id')
            ->select(
                'unallotted_property_details.old_property_id',
                'unallotted_property_details.plot_area_in_sqm',
                'unallotted_property_details.is_litigation',
                'unallotted_property_details.is_encrached',
                'unallotted_property_details.is_vaccant',
                'unallotted_property_details.is_transferred',
                'unallotted_property_details.transferred_to',
                'unallotted_property_details.is_property_document_exist',
                'unallotted_property_details.date_of_transfer',
                'unallotted_property_details.purpose',
                'unallotted_property_details.created_at',
                'property_masters.unique_propert_id',
                'property_masters.land_type',
                'property_masters.new_colony_name',
                'items.item_name as landType',
                'old_colonies.name as colonyName',
                'departments.name as departmentName',
            );

        // Define the searchable columns
        $searchableColumns = [
            'unallotted_property_details.old_property_id',
            'unallotted_property_details.date_of_transfer',
            'unallotted_property_details.purpose',
            'property_masters.unique_propert_id',
            'property_masters.land_type',
            'items.item_name',
            'old_colonies.name',
            'departments.name',
        ];

        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Handle pagination, ordering, and filtering
        $limit = $request->input('length');
        $start = $request->input('start');
        $orderColumnIndex = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
            $totalFiltered = $query->count();
        }

        $getUnallotedPropertyData = $query->offset($start)
            ->limit($limit)
            ->orderBy($searchableColumns[$orderColumnIndex] ?? 'unallotted_property_details.created_at', $dir)
            ->get();

        $counter = 1; // Initialize counter for auto-increment
        $data = [];
        foreach ($getUnallotedPropertyData as $property) {
            $propertyHTML = $documentHTML = '';
            $nestedData['id'] = $counter++; // Auto-incremented ID
            $propertyHTML .= '<div class="text-primary">'.$property->unique_propert_id.'</div><span class="text-secondary">('.$property->old_property_id.')</span>';
            $nestedData['unique_propert_id'] = $propertyHTML;    
            // $nestedData['unique_propert_id'] = $property->unique_propert_id;
            // $nestedData['old_property_id'] = $property->old_property_id;
            $nestedData['landType'] = $property->landType;
            $nestedData['colonyName'] = $property->colonyName;
            $nestedData['is_property_document_exist'] = $property->is_property_document_exist ? 'Yes' : 'No';
            $nestedData['plot_area_in_sqm'] = $property->plot_area_in_sqm;
            $nestedData['is_vaccant'] = $property->is_vaccant ? 'Yes' : 'No';
            if (!empty($property->is_transferred)) {
                $documentHTML .= !empty($property->departmentName) 
                    ? '<div class="text-secondary">' . htmlspecialchars($property->departmentName) . '</div>' 
                    : '';
            
                $documentHTML .= !empty($property->date_of_transfer) 
                    ? '<div><span class="text-secondary">Transfer Date : ' . \Carbon\Carbon::parse($property->date_of_transfer)->format('d/m/Y') . '</span></div>' 
                    : '';
            
                $documentHTML .= !empty($property->purpose) 
                    ? '<div><span class="text-secondary">Purpose : ' . htmlspecialchars($property->purpose) . '</span></div>' 
                    : '';
            } else {
                $documentHTML .= '<span>No</span>';
            }
            
            $nestedData['is_transferred'] = $documentHTML;
            // $nestedData['is_transferred'] = $property->is_transferred ? $property->departmentName : 'Null';
            $nestedData['is_encrached'] = $property->is_encrached ? 'Yes' : 'No';
            $nestedData['is_litigation'] = $property->is_litigation ? 'Yes' : 'No';
            $data[] = $nestedData;
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ];

        return response()->json($json_data);
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
