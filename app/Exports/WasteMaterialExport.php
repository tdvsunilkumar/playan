<?php

namespace App\Exports;

use App\Models\BacRfqSupplierCanvass;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\GsoDepartmentalRequestItem;
use App\Models\GsoPurchaseOrderPostingLine;
use DB;
use DateTime;

class WasteMaterialExport implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
    private $keywords, $status;

    public function __construct(String $keywords = null)
    {
        $this->keywords = $keywords;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 12,
            'C' => 10,
            'D' => 24,
            'E' => 24,
            'F' => 24,
            'G' => 18,
            'H' => 16,
            'I' => 16,
        ];
    }

    public function registerEvents(): array
    {   
        $keywords = $this->keywords;

        $res = GsoDepartmentalRequestItem::select([
            'gso_departmental_requests_items.*',
            'gso_purchase_orders.id as poID',
            'bac_rfqs.id as rfqID',
            'gso_suppliers.business_name as supplier',
            'gso_purchase_orders.purchase_order_no as po_no',
            'gso_purchase_orders.purchase_order_date as po_date'
        ])
        ->join('gso_items', function($join)
        {
            $join->on('gso_items.id', '=', 'gso_departmental_requests_items.item_id');
        })
        ->join('gso_unit_of_measurements', function($join)
        {
            $join->on('gso_unit_of_measurements.id', '=', 'gso_departmental_requests_items.uom_id');
        })
        ->join('gso_departmental_requests', function($join)
        {
            $join->on('gso_departmental_requests.id', '=', 'gso_departmental_requests_items.departmental_request_id');
        })
        ->join('gso_purchase_request_types', function($join)
        {
            $join->on('gso_purchase_request_types.id', '=', 'gso_departmental_requests.request_type_id');
        })
        ->join('gso_purchase_requests', function($join)
        {
            $join->on('gso_purchase_requests.departmental_request_id', '=', 'gso_departmental_requests.id');
        })
        ->join('bac_rfqs_lines', function($join)
        {
            $join->on('bac_rfqs_lines.purchase_request_id', '=', 'gso_purchase_requests.id');
        })
        ->join('bac_rfqs', function($join)
        {
            $join->on('bac_rfqs.id', '=', 'bac_rfqs_lines.rfq_id');
        })
        ->join('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.rfq_id', '=', 'bac_rfqs.id');
        })
        ->join('gso_suppliers', function($join)
        {
            $join->on('gso_suppliers.id', '=', 'gso_purchase_orders.supplier_id');
        })
        ->where([
            'gso_purchase_request_types.id' => 3,
            'bac_rfqs_lines.is_active' => 1,
            'gso_departmental_requests_items.is_active' => 1
        ])
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('gso_departmental_requests_items.quantity_po', 'like', '%' . $keywords . '%')
                ->orWhere('gso_unit_of_measurements.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.code', 'like', '%' . $keywords . '%')
                ->orWhere('gso_items.name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_suppliers.business_name', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.purchase_order_no', 'like', '%' . $keywords . '%')
                ->orWhere('gso_purchase_orders.purchase_order_date', 'like', '%' . $keywords . '%');
            }
        })
        ->groupBy(['gso_items.id','gso_purchase_orders.id'])
        ->get();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords) {
                $event->sheet->getDelegate()->mergeCells('A1:I1');
                $event->sheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->mergeCells('A2:I2');
                $event->sheet->getStyle('A2:I2')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->mergeCells('A3:I3');
                $event->sheet->getStyle('A3:I3')->getAlignment()->setHorizontal('center');
                $event->sheet->setCellValue('A1', 'Generated Waste Materials Sheet');
                $event->sheet->setCellValue('A3', 'Keywords: ' . $keywords);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $styleArray2 = [
                    'font' => [
                        // 'name'      =>  'Calibri',
                        'size'      =>  13,
                        'bold'      =>  true
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'c4c5d6']
                    ]
                ];

                $styleArray3 = [
                    'font' => [
                        // 'name'      =>  'Calibri',
                        // 'size'      =>  12,
                        'color' => ['rgb' => 'ffffff'],
                        'bold'      =>  true
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '99999e']
                    ]
                ];

                $rows = 5;
                if (!empty($res)) {
                    $event->sheet->getStyle('A'.$rows.':I'.$rows)->getAlignment()->setHorizontal('center');
                    $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('I'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->setCellValue('A'.$rows, '#'); 
                    $event->sheet->setCellValue('B'.$rows, 'QUANTITY'); 
                    $event->sheet->setCellValue('C'.$rows, 'UOM'); 
                    $event->sheet->setCellValue('D'.$rows, 'ITEM DESCRIPTION'); 
                    $event->sheet->setCellValue('E'.$rows, 'SUPPLIER'); 
                    $event->sheet->setCellValue('F'.$rows, 'PO DETAILS'); 
                    $event->sheet->setCellValue('G'.$rows, 'OR NO'); 
                    $event->sheet->setCellValue('H'.$rows, 'AMOUNT'); 
                    $event->sheet->setCellValue('I'.$rows, 'TOTAL AMOUNT'); 
                    $rows++; $amount = 0; $total = 0; $iteration = 0;
                    foreach ($res as $waste) {
                        $description = $waste->item ? $waste->item->code . ' - ' . $waste->item->name : ''; 
                        $supplier = $waste->supplier ? $waste->supplier : ''; 
                        $iteration++;
                        $reference_no = $this->get_po_reference_no($waste->poID, $waste->item->id);
                        $unitCost = $this->getItemCost($waste->rfqID, $waste->item->id);
                        $totalCost = floatval($unitCost) * floatval($waste->quantity_po);
                        $amount += floatval($unitCost);
                        $total += floatval($totalCost);
                        $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('I'.$rows)->applyFromArray($styleArray);
                        $event->sheet->setCellValue('A'.$rows, $iteration); 
                        $event->sheet->getStyle('A'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('B'.$rows, $waste->quantity_po); 
                        $event->sheet->getStyle('B'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('C'.$rows, $waste->uom->code); 
                        $event->sheet->getStyle('C'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('D'.$rows, $description); 
                        $event->sheet->setCellValue('E'.$rows, $supplier); 
                        $event->sheet->getStyle('F'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('F'.$rows, $waste->po_no.' '.date('d-M-Y', strtotime($waste->po_date))); 
                        $event->sheet->getStyle('G'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('G'.$rows, $reference_no); 
                        $event->sheet->setCellValue('H'.$rows, $this->money_format($unitCost)); 
                        $event->sheet->getStyle('H'.$rows)->getAlignment()->setHorizontal('right');
                        $event->sheet->setCellValue('I'.$rows, $this->money_format($totalCost)); 
                        $event->sheet->getStyle('I'.$rows)->getAlignment()->setHorizontal('right');
                        $rows++;
                    }
                    $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('H'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('I'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getDelegate()->mergeCells('A'.$rows.':G'.$rows);
                    $event->sheet->setCellValue('A'.$rows, ''); 
                    $event->sheet->getStyle('H'.$rows)->getAlignment()->setHorizontal('right');
                    $event->sheet->setCellValue('H'.$rows, $this->money_format($amount)); 
                    $event->sheet->getStyle('I'.$rows)->getAlignment()->setHorizontal('right');
                    $event->sheet->setCellValue('I'.$rows, $this->money_format($total)); 
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            // 1    => ['font' => ['bold' => true]],

            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            'A'  => ['font' => ['size' => 12]],
            'B'  => ['font' => ['size' => 12]],
            'C'  => ['font' => ['size' => 12]],
            'D'  => ['font' => ['size' => 12]],
            'E'  => ['font' => ['size' => 12]],
            'F'  => ['font' => ['size' => 12]],
            'G'  => ['font' => ['size' => 12]],
            'H'  => ['font' => ['size' => 12]],
            'I'  => ['font' => ['size' => 12]],
            'J'  => ['font' => ['size' => 12]],
            'K'  => ['font' => ['size' => 12]],
            'L'  => ['font' => ['size' => 12]],
            'M'  => ['font' => ['size' => 12]],
            'N'  => ['font' => ['size' => 12]],

            'A1'  => ['font' => ['size' => 24, 'bold' => true]],
            'A2'  => ['font' => ['size' => 18, 'bold' => true]],
            'A3:F3'  => ['font' => ['size' => 14]],
            'A4:F4'  => ['font' => ['size' => 13]],
        ];
    }

    public function getItemCost($rfqID, $itemID) 
    {   
        $res = BacRfqSupplierCanvass::select('unit_cost')
        ->leftJoin('bac_rfqs_suppliers', function($join)
        {
            $join->on('bac_rfqs_suppliers.rfq_id', '=', 'bac_rfqs_suppliers_canvass.rfq_id');
        })
        ->whereRaw('bac_rfqs_suppliers_canvass.supplier_id = bac_rfqs_suppliers.supplier_id 
            AND bac_rfqs_suppliers.rfq_id = '.$rfqID.' 
            and bac_rfqs_suppliers_canvass.item_id = '.$itemID.' 
            and bac_rfqs_suppliers.is_selected = 1')
        ->get();

        if ($res->count() > 0) {
            return $res->first()->unit_cost;
        }
        return floatval(0);
    }

    public function get_po_reference_no($poID, $itemID)
    {
        $res = GsoPurchaseOrderPostingLine::select(['reference_no'])
        ->join('gso_purchase_orders_posting', function($join)
        {
            $join->on('gso_purchase_orders_posting.id', '=', 'gso_purchase_orders_posting_lines.posting_id');
        })
        ->join('gso_purchase_orders', function($join)
        {
            $join->on('gso_purchase_orders.id', '=', 'gso_purchase_orders_posting.purchase_order_id');
        })
        ->where([
            'gso_purchase_orders_posting_lines.is_active' => 1,
            'gso_purchase_orders_posting_lines.item_id' => $itemID,
            'gso_purchase_orders.id' => $poID
        ])
        ->groupBy(['gso_purchase_orders_posting.reference_no'])
        ->get();

        $arr = array();
        if (!empty($res)) {
            foreach ($res as $r) {
                $arr[] = $r->reference_no;
            }
        }

        return (count($arr) > 0) ? implode(',', $arr) : '';
    }

    public function view(): View
    {   
        return view('general-services.waste-materials.export', ['keywords' => $this->keywords]);
    }

    public function money_format($money)
    {
        if (floatval($money) > 0) {
            return '₱' . number_format(floor(($money*100))/100, 2);
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'WASTE MATERIAL SHEET';
    }
}