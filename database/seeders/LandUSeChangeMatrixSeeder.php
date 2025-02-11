<?php

namespace Database\Seeders;

use App\Models\LandUseChangeMatrix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandUSeChangeMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matrx = [
            [
                'from' => ['property_type' => 47, 'property_sub_type' => 17], //residential plot
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 1355, 'rate' => 2], //residential multistorey building
                    ['property_type' => 48, 'property_sub_type' => 17, 'rate' => 20], // comercial plot
                    ['property_type' => 48, 'property_sub_type' => 1355, 'rate' => 20], // commercial msb
                    ['property_type' => 469, 'property_sub_type' => 17, 'rate' => 20], // Industrial Plot
                    ['property_type' => 48, 'property_sub_type' => 403, 'rate' => 20], //commercial hotel
                    ['property_type' => 48, 'property_sub_type' => 407, 'rate' => 20] // commercial cinema

                ]
            ],
            [
                'from' => ['property_type' => 47, 'property_sub_type' => 1355], //residential multistorey building
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17, 'rate' => 2], //residential plot
                    ['property_type' => 48, 'property_sub_type' => 17, 'rate' => 20], // comercial plot
                    ['property_type' => 48, 'property_sub_type' => 1355, 'rate' => 20], // commercial msb
                    ['property_type' => 469, 'property_sub_type' => 17, 'rate' => 20], // Industrial Plot
                    ['property_type' => 48, 'property_sub_type' => 403, 'rate' => 20], //commercial hotel
                    ['property_type' => 48, 'property_sub_type' => 407, 'rate' => 20] // commercial cinema
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 17], //commercail plot
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17, 'rate' => 2], //residential plot 
                    ['property_type' => 47, 'property_sub_type' => 1355, 'rate' => 2], //residential MSB
                    ['property_type' => 48, 'property_sub_type' => 1355, 'rate' => 2], // Commercial MSB
                    ['property_type' => 469, 'property_sub_type' => 17, 'rate' => 2], // Industrial Plot
                    ['property_type' => 48, 'property_sub_type' => 403, 'rate' => 20], //commercial hotel
                    ['property_type' => 48, 'property_sub_type' => 407, 'rate' => 20] // commercial cinema

                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 1355], //commercial MSB
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17, 'rate' => 2], //residential plot 
                    ['property_type' => 47, 'property_sub_type' => 1355, 'rate' => 2], //residential MSB
                    ['property_type' => 48, 'property_sub_type' => 17, 'rate' => 2], //commercila plot
                    ['property_type' => 469, 'property_sub_type' => 17, 'rate' => 2], //industrial plot
                    ['property_type' => 48, 'property_sub_type' => 403, 'rate' => 20], //commercial hotel
                    ['property_type' => 48, 'property_sub_type' => 407, 'rate' => 20] // commercial cinema
                ]
            ],
            [
                'from' => ['property_type' => 469, 'property_sub_type' => 17], //industrial plot
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17, 'rate' => 2], // residential plot
                    ['property_type' => 47, 'property_sub_type' => 1355, 'rate' => 2], // residential MSB
                    ['property_type' => 48, 'property_sub_type' => 17, 'rate' => 20], // comercial plot
                    ['property_type' => 48, 'property_sub_type' => 1355, 'rate' => 20], // commercial msb
                    ['property_type' => 48, 'property_sub_type' => 403, 'rate' => 20], //commercial hotel
                    ['property_type' => 48, 'property_sub_type' => 407, 'rate' => 20] // commercial cinema
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 403], // commercial hotel
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17, 'rate' => 2], // residential plot
                    ['property_type' => 47, 'property_sub_type' => 1355, 'rate' => 2], // residential MSB
                    ['property_type' => 48, 'property_sub_type' => 17, 'rate' => 2], // comercial plot
                    ['property_type' => 48, 'property_sub_type' => 1355, 'rate' => 2], // commercial msb
                    ['property_type' => 469, 'property_sub_type' => 17, 'rate' => 2], //industrial plot
                    ['property_type' => 48, 'property_sub_type' => 407, 'rate' => 2] // commercial cinema
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 407], //commercial cinema
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17, 'rate' => 2],
                    ['property_type' => 47, 'property_sub_type' => 1355, 'rate' => 2],
                    ['property_type' => 48, 'property_sub_type' => 17, 'rate' => 2],
                    ['property_type' => 48, 'property_sub_type' => 1355, 'rate' => 2],
                    ['property_type' => 469, 'property_sub_type' => 17, 'rate' => 2],
                    ['property_type' => 48, 'property_sub_type' => 403, 'rate' => 2]
                ]
            ],
        ];

        $date_from = date('Y-01-01');

        foreach ($matrx as $prop) {
            $from = $prop['from'];
            $to = $prop['to'];
            foreach ($to as $row) {
                LandUseChangeMatrix::updateOrInsert([
                    'property_type_from' => $from['property_type'],
                    'property_sub_type_from' => $from['property_sub_type'],
                    'property_type_to' => $row['property_type'],
                    'property_sub_type_to' => $row['property_sub_type'],
                ], [
                    'date_from' => $date_from,
                    'rate' => $row['rate']
                ]);
            }
        }
    }
}
