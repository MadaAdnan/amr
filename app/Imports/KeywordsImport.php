<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Color;


class KeywordsImport implements FromCollection , WithColumnWidths,WithStyles
{
    protected $keywords;

    public function __construct($keywords)
    {
        $this->keywords = $keywords;
    }

    public function collection()
    {
        $data =  $this->keywords->map(function($item)
        {

            $totalBlogs = $item->post_keywords()->count() == 0 ?  $item->post_keywords()->count():1;
            $totalPages = $item->pages_keywords()->count() == 0? $item->pages_keywords()->count():1;

            return[
                'title'=>$item->title ?? 'No Data',
                'subject'=>$item->subject??'No Data',
                'strength'=>$item->strength??'No Data',
                'monthly_volume'=>$item->monthly_volume??'No Data',
                'blogs'=>$totalBlogs,
                'pages'=>$totalPages,
                'Total'=>$totalBlogs+$totalPages,
                'created'=>$item->created_at->format('Y/M/d')
            ];
        })->toArray();

        $cols = ['Keyword Name','Subject','Strength','Monthly volume','Blogs','Pages','Total','Created'];
        array_unshift($data, $cols);

        return collect($data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 40,            
            'C' => 20,
            'D' => 25,            
            'E' => 10,            
            'F' => 10,            
            'G' => 10,            
            'H' => 40,            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true],
                 'font' => ['size' => 14],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'startColor' => ['argb' => Color::COLOR_RED],                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ]
            ],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath("https://img.freepik.com/free-photo/blue-black-muscle-car-with-license-plate-that-says-trans-front_1340-23399.jpg?w=2000");
        $drawing->setHeight(90);
        $drawing->setCoordinates('B3');

        return $drawing;
    }




}
