<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExportBkp implements FromCollection, WithHeadings, WithStyles, ShouldQueue
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $filters;
    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    /* public function array(): array
    {
        return $this->rows;
    } */
    public function collection()
    {
        $page = 1;
        $service = new ReportService();
        $rows = [];
        $chunkSize = 1000;
        $filters['limit'] = $chunkSize;
        $results = $service->filterResults($filters)['rows'];
        Log::info("filetrs = $filters");
        $start = time();
        do {
            $results = $service->filterResults($filters)['rows'];
            $page++;
            $now = time();
            if (is_null($results) || empty($results) || ($now - $start) > 600) break;

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
            }
            // If you want to write each chunk to the file progressively
            // You can do so here depending on the format.

        } while (count($results) == $chunkSize); //while ($page < 3); 
        Log::info("rows = $rows");
        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Old Property Id',
            'Unique Property Id',
            'Land Type',
            'Land Status',
            'Lease Tenure',
            'Land Use',
            'Area(SQM)',
            "Address",
            "Lesse/Owner Name",
            'Ground Rent(Rs)',
            'Status of RGR'
        ];
    }

    /*  public function array(): array
    {
        //return $this->output;
        return $this->data;
        /*  $result = [];
        foreach ($this->chunks as $chunk) {
            foreach ($chunk as $item) {
                $result[] = [
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
            }
        }
        return $result; /
    } */

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
