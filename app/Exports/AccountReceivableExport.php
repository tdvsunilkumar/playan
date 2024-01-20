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
use App\Models\CtoReceivable;
use DB;
use DateTime;

class AccountReceivableExport implements WithEvents, WithStyles, WithColumnWidths, WithTitle, FromView
{
    private $keywords, $status;

    public function __construct(String $keywords = null, String $status = null)
    {
        $this->keywords = $keywords;
        $this->status = $status;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 14,
            'C' => 14,
            'D' => 14,
            'E' => 14,
            'F' => 14,
            'G' => 14,
            'H' => 14,
            'I' => 14,
            'J' => 14,
            'K' => 14,
            'L' => 14,
            'M' => 14,
            'N' => 14,   
        ];
    }

    public function registerEvents(): array
    {   
        $keywords = $this->keywords;
        $status = $this->status;
        $statusClass = [
            0 => (object) ['bg' => 'draft-bg', 'status' => 'Unpaid'],
            1 => (object) ['bg' => 'purchased-bg', 'status' => 'Partial'],
            2 => (object) ['bg' => 'completed-bg', 'status' => 'Paid'],
        ];
        
        $result = CtoReceivable::select([
            'cto_receivables.*',
            DB::raw('SUM(cto_receivables.amount_due) as totalAmtDue'),
            DB::raw('SUM(cto_receivables.amount_pay) as totalAmtPay'),
            DB::raw('SUM(cto_receivables.remaining_amount) as totalAmtBal')
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_receivables.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_receivables.amount_due', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.description', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.amount_pay', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.remaining_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.due_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_receivables.is_active', '=', 1);
        if ($status != 'all') {
            $result->where('cto_receivables.is_paid', $status);
        }
        $result = $result->get();
        

        $res = CtoReceivable::select([
            'cto_receivables.*',
        ])
        ->leftJoin('acctg_account_general_ledgers', function($join)
        {
            $join->on('acctg_account_general_ledgers.id', '=', 'cto_receivables.gl_account_id');
        })
        ->where(function($q) use ($keywords) {
            if (!empty($keywords)) {
                $q->where('cto_receivables.amount_due', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.code', 'like', '%' . $keywords . '%')
                ->orWhere('acctg_account_general_ledgers.description', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.description', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.amount_pay', 'like', '%' . $keywords . '%')    
                ->orWhere('cto_receivables.remaining_amount', 'like', '%' . $keywords . '%')
                ->orWhere('cto_receivables.due_date', 'like', '%' . $keywords . '%');
            }
        })
        ->where('cto_receivables.is_active', '=', 1);
        if ($status != 'all') {
            $res = $res->where('cto_receivables.is_paid', $status);
        }
        $res = $res->get();

        return [
            AfterSheet::class => function(AfterSheet $event) use ($res, $keywords, $status, $statusClass) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->mergeCells('A2:G2');
                $event->sheet->getStyle('A1:F2')->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('A3')->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('B3')->getAlignment()->setHorizontal('left');
                $event->sheet->setCellValue('A3', 'Keywords:');
                $event->sheet->setCellValue('B3', $keywords);
                $event->sheet->getStyle('F3')->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('G3')->getAlignment()->setHorizontal('left');
                $event->sheet->setCellValue('F3', 'Status:');
                $event->sheet->setCellValue('G3', ($status != 'all') ? $statusClass[$status]->status : 'All' );

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
                    $event->sheet->getStyle('A'.$rows.':G'.$rows)->getAlignment()->setHorizontal('center');
                    $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->setCellValue('A'.$rows, 'GL ACCOUNT'); 
                    $event->sheet->setCellValue('B'.$rows, 'ITEM DESCRIPTION'); 
                    $event->sheet->setCellValue('C'.$rows, 'AMOUNT DUE'); 
                    $event->sheet->setCellValue('D'.$rows, 'AMOUNT PAID'); 
                    $event->sheet->setCellValue('E'.$rows, 'REMAINING BALANCE'); 
                    $event->sheet->setCellValue('F'.$rows, 'DUE DATE'); 
                    $event->sheet->setCellValue('G'.$rows, 'STATUS'); 
                    $rows++; $totalDue = 0; $totalPay = 0; $totalBalance = 0;
                    foreach ($res as $receivable) {
                        $gl_account = $receivable->gl_account ? $receivable->gl_account->code . ' - ' . $receivable->gl_account->description : ''; 
                        $description = $receivable->description ? $receivable->description : '';
                        $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray);
                        $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray);
                        $event->sheet->setCellValue('A'.$rows, $gl_account); 
                        $event->sheet->setCellValue('B'.$rows, $description); 
                        $event->sheet->getStyle('C'.$rows)->getAlignment()->setHorizontal('right');
                        $event->sheet->setCellValue('C'.$rows, $this->money_format($receivable->amount_due)); 
                        $event->sheet->getStyle('D'.$rows)->getAlignment()->setHorizontal('right');
                        $event->sheet->setCellValue('D'.$rows, $this->money_format($receivable->amount_pay)); 
                        $event->sheet->getStyle('E'.$rows)->getAlignment()->setHorizontal('right');
                        $event->sheet->setCellValue('E'.$rows, $this->money_format($receivable->remaining_amount)); 
                        $event->sheet->getStyle('F'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('F'.$rows, date('d-M-Y', strtotime($receivable->due_date))); 
                        $event->sheet->getStyle('G'.$rows)->getAlignment()->setHorizontal('center');
                        $event->sheet->setCellValue('G'.$rows, $statusClass[$receivable->is_paid]->status); 
                        if (floatval($receivable->amount_due) > 0) {
                            $totalDue += floatval($receivable->amount_due);
                        }
                        if (floatval($receivable->amount_pay) > 0) {
                            $totalPay += floatval($receivable->amount_pay);
                        }
                        if (floatval($receivable->remaining_amount) > 0) {
                            $totalBalance += floatval($receivable->remaining_amount);
                        }
                        $rows++;
                    }
                    $event->sheet->getStyle('A'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('B'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('C'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('D'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('E'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('F'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->getStyle('G'.$rows)->applyFromArray($styleArray3);
                    $event->sheet->setCellValue('A'.$rows, ''); 
                    $event->sheet->setCellValue('B'.$rows, ''); 
                    $event->sheet->getStyle('C'.$rows)->getAlignment()->setHorizontal('right');
                    $event->sheet->setCellValue('C'.$rows, $this->money_format($totalDue)); 
                    $event->sheet->getStyle('D'.$rows)->getAlignment()->setHorizontal('right');
                    $event->sheet->setCellValue('D'.$rows, $this->money_format($totalPay)); 
                    $event->sheet->getStyle('E'.$rows)->getAlignment()->setHorizontal('right');
                    $event->sheet->setCellValue('E'.$rows, $this->money_format($totalBalance)); 
                    $event->sheet->getStyle('F'.$rows)->getAlignment()->setHorizontal('center');
                    $event->sheet->setCellValue('F'.$rows, ''); 
                    $event->sheet->getStyle('G'.$rows)->getAlignment()->setHorizontal('center');
                    $event->sheet->setCellValue('G'.$rows, ''); 
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

    public function view(): View
    {   
        return view('reports.accounting.account-receivables.view', ['keywords' => $this->keywords, 'status' => $this->status]);
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
        return 'ACCOUNT RECEIVABLE SHEET';
    }
}