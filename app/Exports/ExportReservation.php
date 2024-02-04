<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class ExportReservation implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $reservations;

    public function __construct($reservations)
    {
        $this->reservations = $reservations;
    }

    public function collection()
    {
        $i = 0;
        $totalSum = $this->reservations->sum('price_with_tip');

        $data = $this->reservations->map(function ($reservation, $i) use ($totalSum) {
            $pickupTime = Carbon::parse($reservation->pick_up_time, 'America/New_York');

            $startTime = Carbon::parse('21:00:00', 'America/New_York');
            $endTime = Carbon::parse('06:00:00', 'America/New_York')->addDay();

            $isNighttime = $pickupTime->isBetween($startTime, $endTime);

            $fee = $isNighttime ? 16.0 : 'N/A';
            return [
                'id' => ++$i,
                'LR Booking Number' => $reservation->id,
                'Service type' => isset($reservation->serviceType) ? $reservation->serviceType->service_name : 'N/A',
                'Pickup date' => $reservation->pick_up_date ? $reservation->pick_up_date->format('Y-m-d') : 'N/A',
                'Pickup time' => $reservation->pick_up_time ? $reservation->pick_up_time : 'N/A',
                'Fleet Category' => isset($reservation->fleets) ? $reservation->fleets->title : 'N/A',
                'Miles' => 0,
                'Price' => $reservation->price ? $reservation->price : 'N/A',
                'Coupon Discount' => isset($reservation->coupons) ? $reservation->coupons->percentage_discount : 'N/A',
                'Discount Type' => isset($reservation->coupons) ? $reservation->coupons->discount_type : 'N/A',
                'Creation Date' => $reservation->created_at,
            ];

        });
        $data->push([
            'id' => '',
            'LR Booking Number' => '',
            'Service type' => '',
            'Pickup date' => '',
            'Pickup time' => '',
            'Fleet Category' => '',
            'Miles' => '',
            'Price' => '',
            'Coupon Discount' => '',
            'Discount Type' => '',
            'Creation Date'=>'Total:',
            'Total' => $totalSum,
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'id',
            'LR Booking Number',
            'Service type',
            'Pickup date',
            'Pickup time',
            'Fleet Category',
            'Miles',
            'Price',
            'Coupon Discount ',
            'Discount Type',
            'Creation Date',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '776a59'],
                ],
            ]
        ];

        $lastRow = $sheet->getHighestDataRow();
        $lastColumn = $sheet->getHighestDataColumn();

        $borderRange = "A1:$lastColumn$lastRow";

        $sheet->getStyle($borderRange)->applyFromArray($borderStyle);

        $sheet->getStyle("A$lastRow:$lastColumn$lastRow")->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Auto-adjust column widths to fit the content
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Center the data horizontally
        $sheet->getStyle("A1:$lastColumn$lastRow")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f7c180'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
            return [
                'A' => 10,
                'B' => 10,
                'C' => 10,
                'D' => 10,
                'E' => 10,
                'F' => 10,
                'G' => 10,
                'H' => 10,
                'I' => 10,
                'J' => 10,
                'K' => 10,
                'L' => 10,
                'M' => 10,
                'N' => 10,
                'O' => 10,
                'P' => 10,
                'Q' => 10,
                'R' => 10,
                'S' => 10,
                'T' => 10,
                'U' => 10,
                'V' => 10,
                'W' => 10,
                'X' => 10,
                'Y' => 10,
                'Z' => 10,
                'AA' => 10,
            ];
        }

    }