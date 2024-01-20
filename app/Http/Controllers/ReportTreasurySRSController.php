<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportTreasurySRSController extends Controller
{
    private ReportTreasuryCollectionInterface $reportTreausryCollectionRepository;
    private CtoCollectionInterface $ctoCollectionRepository;
    private $carbon;
    private $slugs;

    public function __construct(
        Carbon $carbon
    ) {
        date_default_timezone_set('Asia/Manila');
        $this->carbon = $carbon;
        $this->slugs = 'reports/treasury/statement-of-reciepts-sources';
    }

    public function index(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        // $export_as = ['' => 'Select export type', 'pageview' => 'Page View', 'excel' => 'Excel', 'pdf' => 'PDF'];
        $export_as = ['' => 'Select export type', 'pdf' => 'PDF'];
        $orders = ['' => 'select order by', 'ASC' => 'Ascending', 'DESC' => 'Descending'];
        return view('reports.treasury.srs.index')->with(compact('export_as', 'orders'));
    }

    public function export_to_pdf(Request $request)
    {   
        $this->is_permitted($this->slugs, 'read');
        $rows = [
            [
                'name' => 'LOCAL SOURCES',
            ],
            [
                'name' => 'TAX REVENUE',
                'data' => [
                    [
                        'name' => 'REAL PROPERTY TAX',
                        'data' => [
                            [
                                'name' => 'Real Property Tax - Basic',
                                'data' => [
                                    [
                                        'name' => 'Current Year'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Current Year'
                                    ],
                                    [
                                        'name' => 'Prior Year/s'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Prior Year/s'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Special Levy on Idle Lands',
                                'data' => [
                                    [
                                        'name' => 'Current Year'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Current Year'
                                    ],
                                    [
                                        'name' => 'Prior Year/s'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Prior Year/s'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Special Levy on Land Benefited by Public Works Projects',
                                'data' => [
                                    [
                                        'name' => 'Current Year'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Current Year'
                                    ],
                                    [
                                        'name' => 'Prior Year/s'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Prior Year/s'
                                    ],
                                ]
                            ]
                        ]
                    ],
                    [
                        'name' => 'TAX ON BUSINESS',
                        'data' => [
                            [
                                'name' => 'Amusement Tax',
                            ],
                            [
                                'name' => 'Business Tax',
                                'data' => [
                                    [
                                        'name' => 'Manufacturers, Assembler, etc.'
                                    ],
                                    [
                                        'name' => 'Wholesalers, Distributors, etc.'
                                    ],
                                    [
                                        'name' => 'Exporter, Manufacturers, Dealers, etc.'
                                    ],
                                    [
                                        'name' => 'Retailers'
                                    ],
                                    [
                                        'name' => 'Contractors and other independent contractors'
                                    ],
                                    [
                                        'name' => 'Banks & Other Financial Institutions'
                                    ],
                                    [
                                        'name' => 'Peddlers'
                                    ],
                                    [
                                        'name' => 'Printing & Publication Tax'
                                    ],
                                    [
                                        'name' => 'Tax on Amusement Place'
                                    ],
                                    [
                                        'name' => 'Other Business Taxes'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Franchise Tax',
                            ],
                            [
                                'name' => 'Tax on Delivery Trucks and Vans',
                            ],
                            [
                                'name' => 'Tax on Sand, Gravel & Other Quarry Resources',
                            ],
                            [
                                'name' => 'Fines and Penalties - Business Taxes',
                            ]
                        ]
                    ],
                    [
                        'name' => 'OTHER TAXES',
                        'data' => [
                            [
                                'name' => 'Community Tax - Corporation',
                            ],
                            [
                                'name' => 'Community Tax - Individual',
                            ],
                            [
                                'name' => 'Professional Tax',
                            ],
                            [
                                'name' => 'Real Property Transfer Tax',
                            ],
                            [
                                'name' => 'Other Taxes',
                            ],
                            [
                                'name' => 'Fines and Penalties - Other Taxes',
                            ]
                        ]
                    ]
                ]
            ],

            [
                'name' => 'NON-TAX REVENUES',
                'data' => [
                    [
                        'name' => 'REGULATORY FEES (Permit and Licenses)',
                        'data' => [
                            [
                                'name' => 'Permit and Licenses',
                                'data' => [
                                    [
                                        'name' => 'Fees on Weights and Measures'
                                    ],
                                    [
                                        'name' => 'Fishery Rentsl Fees and Privilege Fees'
                                    ],
                                    [
                                        'name' => 'Franchising and Licensing Fees'
                                    ],
                                    [
                                        'name' => 'Business Permit Fees'
                                    ],
                                    [
                                        'name' => 'Zonal/Location Permit Fees'
                                    ],
                                    [
                                        'name' => 'Tricycle Operators Permit Fees'
                                    ],
                                    [
                                        'name' => 'Occupational Fees'
                                    ],
                                    [
                                        'name' => 'Other Permit & Licenses'
                                    ]
                                ]
                            ],
                            [
                                'name' => 'Registration Fees',
                                'data' => [
                                    [
                                        'name' => 'Cattle/Animal Registration Fees'
                                    ],
                                    [
                                        'name' => 'Civil Registration Fees'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Inspection Fees',
                            ],
                            [
                                'name' => 'Fines and Penalties - Permit and Licenses',
                            ]
                        ]
                    ],
                    [
                        'name' => 'SERVICE/USER CHARGES (SERVICE INCOME)',
                        'data' => [
                            [
                                'name' => 'Clearance and Certification Fees',
                                'data' => [
                                    [
                                        'name' => 'Police Clearance'
                                    ],
                                    [
                                        'name' => "Secretary's Fees"
                                    ],
                                    [
                                        'name' => 'Health Certificate'
                                    ],
                                    [
                                        'name' => 'Other Clearance and Certification'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Other Fees',
                                'data' => [
                                    [
                                        'name' => 'Garbage Fees'
                                    ],
                                    [
                                        'name' => 'Wharfage Fees'
                                    ],
                                    [
                                        'name' => 'Toll Fees'
                                    ],
                                    [
                                        'name' => 'Other Service Income'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Fines and Penalties - Service Income',
                            ],
                            [
                                'name' => 'Landing and Aeronautical Fees',
                            ],
                            [
                                'name' => 'Parking and Terminal Fees',
                            ],
                            [
                                'name' => 'Hospital Fees',
                            ],
                            [
                                'name' => 'Medical, Dental and Laboratory Fees',
                            ],
                            [
                                'name' => 'Market & Slaughterhouse Fees',
                            ],
                            [
                                'name' => 'Printing and Publication Fees',
                            ]
                        ]
                    ],
                    [
                        'name' => 'INCOME FROM ECONOMIC ENTERPRISES (BUSINESS INCOME)',
                        'data' => [
                            [
                                'name' => 'Income from Economic ',
                                'data' => [
                                    [
                                        'name' => 'School Operations'
                                    ],
                                    [
                                        'name' => "Power Generations/Distribution"
                                    ],
                                    [
                                        'name' => 'Hospital Operations'
                                    ],
                                    [
                                        'name' => 'Canteen/Restaurant Operations'
                                    ],
                                    [
                                        'name' => 'Cemetery Operations'
                                    ],
                                    [
                                        'name' => 'Communication Facilities & Equipment Operations'
                                    ],
                                    [
                                        'name' => 'Dormitory Operations'
                                    ],
                                    [
                                        'name' => 'Market Operations'
                                    ],
                                    [
                                        'name' => 'Slaughterhouse Operations'
                                    ],
                                    [
                                        'name' => 'Transportation System Operation'
                                    ],
                                    [
                                        'name' => 'Waterworks System Operations'
                                    ],
                                    [
                                        'name' => 'Printing & Publication Operations'
                                    ],
                                    [
                                        'name' => 'Income from Lease/Rental of Facilities'
                                    ],
                                    [
                                        'name' => 'Income from Trading Business'
                                    ],
                                    [
                                        'name' => 'Other Economic Enterprises'
                                    ],
                                    [
                                        'name' => 'Fines and Penalties - Economic'
                                    ],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => 'Other Receipts (Other General Income)',
                        'data' => [
                            [
                                'name' => 'Interest Income',
                            ],
                            [
                                'name' => 'Dividend Income',
                            ],
                            [
                                'name' => 'Other General Income (Miscellaneous)',
                                'data' => [
                                    [
                                        'name' => 'Rebates on MMDA Contribution'
                                    ],
                                    [
                                        'name' => 'Sales of Confiscated/Abandoned/Seized Goods & Properties'
                                    ],
                                    [
                                        'name' => 'Others'
                                    ],
                                ]
                            ],
                        ]
                    ],
                ]
            ],

            [
                'name' => 'TOTAL INCOME - LOCAL SOURCES',
            ],

            [
                'name' => 'EXTERNAL SOURCES',
                'data' => [
                    [
                        'name' => 'Share from National Tax Collection',
                        'data' => [
                            [
                                'name' => 'Internal Revenue Allotment',
                                'data' => [
                                    [
                                        'name' => 'Current Year'
                                    ],
                                    [
                                        'name' => 'Prior Year'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Other Shares from National Tax Collection',
                                'data' => [
                                    [
                                        'name' => 'Share from Economic Zone'
                                    ],
                                    [
                                        'name' => 'Share from EVAT'
                                    ],
                                    [
                                        'name' => 'Share from National Wealth'
                                    ],
                                    [
                                        'name' => 'Share from PAGCOR/PCSO/Lotto'
                                    ],
                                    [
                                        'name' => 'Share from Tobacco Excise Tax'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Grants and Donations',
                                'data' => [
                                    [
                                        'name' => 'Foreign'
                                    ],
                                    [
                                        'name' => 'Domestic'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Subsidy Income',
                                'data' => [
                                    [
                                        'name' => 'Other Subsidy Income'
                                    ],
                                    [
                                        'name' => 'Subsidy from GOCCs'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Extraordinary Gains and Premiums',
                                'data' => [
                                    [
                                        'name' => 'Gain on FOREX'
                                    ],
                                    [
                                        'name' => 'Gain on Sale of Assets'
                                    ],
                                    [
                                        'name' => 'Premium on Bonds Payable'
                                    ],
                                    [
                                        'name' => 'Gain on Sale of Investments'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Inter - Local Transfers',
                                'data' => [
                                    [
                                        'name' => 'Subsidy from LGUs'
                                    ],
                                    [
                                        'name' => 'Subsidy from Other Funds'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Capital/Investment Receipts',
                                'data' => [
                                    [
                                        'name' => 'Proceeds from Sale of Assets'
                                    ],
                                    [
                                        'name' => 'Proceeds from Sale of Debt Securities of Other Entities'
                                    ],
                                    [
                                        'name' => 'Collection of Loans Receivables'
                                    ],
                                ]
                            ],
                            [
                                'name' => 'Receipts from Loans and Borrowings (Payable)',
                                'data' => [
                                    [
                                        'name' => 'Loans - Foreign'
                                    ],
                                    [
                                        'name' => 'Loans - Domestic'
                                    ],
                                    [
                                        'name' => 'Bonds Flotation'
                                    ],
                                ]
                            ],
                        ]
                    ],
                    
                    
                    
                ]
            ]
            
        ];
        return view('reports.treasury.srs.pageview')->with(compact('rows'));
    }

}
