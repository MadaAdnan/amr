<?php

namespace App\Exports;

use App\Models\Airline;
use Maatwebsite\Excel\Concerns\FromCollection;

class AirlineExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Airline::all();
    }
}
