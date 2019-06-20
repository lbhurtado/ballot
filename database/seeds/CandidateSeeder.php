<?php

use Illuminate\Database\Seeder;
use LBHurtado\Ballot\Models\{Candidate, Position};

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions_array = [
            [
                'position' => 'President',
                'candidates' => [
                    ['sequence' => 1, 'code' => 'MACAPAGAL', 'name' => 'Diosdado Macapagal'],
                    ['sequence' => 2, 'code' => 'GARCIA',    'name' => 'Carlos P. Garcia'],
                    // ['sequence' => 3, 'code' => 'ABCEDE',    'name' => 'Alfredo Abcede'],
                    // ['sequence' => 4, 'code' => 'VILLANUEVA','name' => 'German P. Villanueva'],
                    // ['sequence' => 5, 'code' => 'LLANZA',    'name' => 'Gregorio L. Llanza'],
                    // ['sequence' => 6, 'code' => 'FLORO',    'name' => 'Praxedes Floro'],
                ],
            ],
            [
                'position' => 'Vice-President',
                'candidates' => [
                    ['sequence' => 1, 'code' => 'PELAEZ',    'name' => 'Emmanuel Pelaez'],
                    ['sequence' => 2, 'code' => 'OSMEÑA',    'name' => 'Sergio Osmeña Jr.'],
                    // ['sequence' => 3, 'code' => 'PUYAT',     'name' => 'Gil Puyat'],
                    // ['sequence' => 4, 'code' => 'JUTA',      'name' => 'Chencay Reyes Juta']
                ],
            ],
            [
                'position' => 'Senator',
                'candidates' => [
                    ['sequence' => 1,  'code' => 'MANGLAPUS', 'name' => 'Raul Manglapus'],
                    ['sequence' => 2,  'code' => 'MANAHAN',   'name' => 'Manuel Manahan'],
                    // ['sequence' => 3,  'code' => 'SUMULONG',  'name' => 'Lorenzo Sumulong'],
                    // ['sequence' => 4,  'code' => 'RODRIGO',   'name' => 'Francisco Soc Rodrigo'],
                    // ['sequence' => 5,  'code' => 'ANTONINO',  'name' => 'Gaudencio Antonino'],
                    // ['sequence' => 6,  'code' => 'OSIAS',     'name' => 'Camilo Osías'],
                    // ['sequence' => 7,  'code' => 'KATIGBAK',  'name' => 'Maria Kalaw Katigbak'],
                    // ['sequence' => 8,  'code' => 'ROY',       'name' => 'Jose Roy'],
                    // ['sequence' => 9,  'code' => 'ZIGA',      'name' => 'Tecla Ziga'],
                    // ['sequence' => 10, 'code' => 'PAREDES',   'name' => 'Quintin Paredes'],
                    // ['sequence' => 11, 'code' => 'GONZALES',  'name' => 'Pacita Madrigal-Gonzales'],
                    // ['sequence' => 12, 'code' => 'CLIMACO',   'name' => 'Cesar Climaco'],
                    // ['sequence' => 13, 'code' => 'ALONTO',    'name' => 'Domocao Alonto'],
                    // ['sequence' => 14, 'code' => 'ROSALES',   'name' => 'Decoroso Rosales'],
                    // ['sequence' => 15, 'code' => 'SABIDO',    'name' => 'Pedro Sabido'],
                    // ['sequence' => 16, 'code' => 'CASTAÑO',   'name' => 'Angel Castaño'],
                    // ['sequence' => 17, 'code' => 'ROMERO',    'name' => 'Jose E. Romero'],
                    // ['sequence' => 18, 'code' => 'MARKING',   'name' => 'Agustin Marking'],
                    // ['sequence' => 19, 'code' => 'OFEMARIA',  'name' => 'Francisco Ofemaria'],
                    // ['sequence' => 20, 'code' => 'HIDALGO',   'name' => 'Ernesto Hidalgo'],
                    // ['sequence' => 21, 'code' => 'JAVINEZ',   'name' => 'Leon Javinez Sr.'],
                    // ['sequence' => 22, 'code' => 'BRIONES', 'name' => 'Jose Briones'],
                ],

            ],
            // [
            //     'position' => 'Party-List',
            //     'candidates' => [
            //         ['sequence' => 1, 'code' => 'WOMEN',     'name' => 'Representative for Women'],
            //         ['sequence' => 2, 'code' => 'YOUTH',     'name' => 'Representative for Youth'],
            //         ['sequence' => 3, 'code' => 'SENIOR',    'name' => 'Representative for Senior Citizens'],
            //         ['sequence' => 4, 'code' => 'DISABLED',  'name' => 'Representative for the Disabled Citizens'],
            //     ],
            // ],
        ];

        foreach ($positions_array as $position_record) {
            $position = Position::withName($position_record['position'])->first();
            foreach ($position_record['candidates'] as $candidate_record) {
                Candidate::create($position, $candidate_record);
            }
        }
    }
}
