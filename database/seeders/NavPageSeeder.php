<?php

namespace Database\Seeders;

use App\Models\NavPages;
use Illuminate\Database\Seeder;

class NavPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'title'=>'Home'
            ],
            [
                'title'=>'Services'
            ],
            [
                'title'=>'Fleet Category'
            ],
            [
                'title'=>'About'
            ],
            [
                'title'=>'Help'
            ],
            [
                'title'=>'Countries'
            ],
            [
                'title'=>'Orphan',
                'is_orphan'=>true
            ]
        ];
        
        foreach ($data as $item) {
            NavPages::create($item);
        }
    }
}
