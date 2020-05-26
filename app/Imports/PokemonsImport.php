<?php

namespace App\Imports;

use App\Model\Pokemon;
use Maatwebsite\Excel\Concerns\ToModel;

class PokemonsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pokemon([
            'no'     => $row[0],
            'pic'    => $row[1],
            'name' => $row[2],
            'types' => $row[3],
            'stats' => $row[4],
            'fast_attacks' => $row[5],
            'special_attacks' => $row[6],
        ]);
    }
}
