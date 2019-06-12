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
                    ['code' => 'MACAPAGAL', 'name' => 'Diosdado Macapagal'],
                    ['code' => 'GARCIA',    'name' => 'Carlos P. Garcia'],
                    // ['code' => 'ABCEDE',    'name' => 'Alfredo Abcede'],
                    // ['code' => 'VILLANUEVA','name' => 'German P. Villanueva'],
                    // ['code' => 'LLANZA',    'name' => 'Gregorio L. Llanza'],
                    // ['code' => 'FLORO',    'name' => 'Praxedes Floro'],
                ],
            ],
            [
                'position' => 'Vice-President',
                'candidates' => [
                    ['code' => 'PELAEZ',    'name' => 'Emmanuel Pelaez'],
                    ['code' => 'OSMEÑA',    'name' => 'Sergio Osmeña Jr.'],
                    // ['code' => 'PUYAT',     'name' => 'Gil Puyat'],
                    // ['code' => 'JUTA',      'name' => 'Chencay Reyes Juta']
                ],
            ],
            [
                'position' => 'Senator',
                'candidates' => [
                    ['code' => 'MANGLAPUS', 'name' => 'Raul Manglapus'],
                    ['code' => 'MANAHAN',   'name' => 'Manuel Manahan'],
                    // ['code' => 'SUMULONG',  'name' => 'Lorenzo Sumulong'],
                    // ['code' => 'RODRIGO',   'name' => 'Francisco Soc Rodrigo'],
                    // ['code' => 'ANTONINO',  'name' => 'Gaudencio Antonino'],
                    // ['code' => 'OSIAS',     'name' => 'Camilo Osías'],
                    // ['code' => 'KATIGBAK',  'name' => 'Maria Kalaw Katigbak'],
                    // ['code' => 'ROY',       'name' => 'Jose Roy'],
                    // ['code' => 'ZIGA',      'name' => 'Tecla Ziga'],
                    // ['code' => 'PAREDES',   'name' => 'Quintin Paredes'],
                    // ['code' => 'GONZALES',  'name' => 'Pacita Madrigal-Gonzales'],
                    // ['code' => 'CLIMACO',   'name' => 'Cesar Climaco'],
                    // ['code' => 'ALONTO',    'name' => 'Domocao Alonto'],
                    // ['code' => 'ROSALES',   'name' => 'Decoroso Rosales'],
                    // ['code' => 'SABIDO',    'name' => 'Pedro Sabido'],
                    // ['code' => 'CASTAÑO',   'name' => 'Angel Castaño'],
                    // ['code' => 'ROMERO',    'name' => 'Jose E. Romero'],
                    // ['code' => 'MARKING',   'name' => 'Agustin Marking'],
                    // ['code' => 'OFEMARIA',  'name' => 'Francisco Ofemaria'],
                    // ['code' => 'HIDALGO',   'name' => 'Ernesto Hidalgo'],
                    // ['code' => 'JAVINEZ',   'name' => 'Leon Javinez Sr.'],
                    // ['code' => 'BRIONES', 'name' => 'ose Briones'],
                ],

            ],
        ];

        foreach ($positions_array as $position_record) {
            $position = Position::withName($position_record['position'])->first();
            foreach ($position_record['candidates'] as $candidate_record) {
                Candidate::create($position, $candidate_record);
            }
        }
    }
}
