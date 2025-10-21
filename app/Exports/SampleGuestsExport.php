<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SampleGuestsExport implements FromView, WithStyles
{
    public function view(): View
    {
        // Return an empty array with the required headers
        return view('exports.sample-guests', [
            'guests' => [
                [
                    'name' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'phone' => '+1234567890'
                ],
                [
                    'name' => 'Jane Smith', 
                    'email' => 'jane.smith@example.com',
                    'phone' => '+0987654321'
                ],
                [
                    'name' => 'Bob Johnson',
                    'email' => 'bob.johnson@example.com', 
                    'phone' => '+1122334455'
                ]
            ]
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        return [
            1 => ['font' => ['bold' => true]], // Make header row bold
        ];
    }
}