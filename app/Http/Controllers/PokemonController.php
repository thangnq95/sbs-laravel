<?php

namespace App\Http\Controllers;

use App\Model\Pokemon;
use Illuminate\Http\Request;
use App\Exports\PokemonsExport;
use App\Imports\PokemonsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class PokemonController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function importExportView()
    {
        return view('import');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        return Excel::download(new PokemonsExport, 'users.xlsx');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import()
    {
        $dataRows = Excel::toArray(new PokemonsImport, request()->file('file'));
        $dataPokemons = $dataRows[0];
        $data = [];
        $row['fast_attacks'] = $row['special_attacks'] = [];
        for ($i = 1; $i < count($dataPokemons); $i++) {
            if ($dataPokemons[$i][0] != "") {
                $id = str_replace("#", "", $dataPokemons[$i][0]);
                $row['no'] = $id;
                $row['pic'] = "Null";
                $row['name'] = $dataPokemons[$i][2];
                $row['types'] =  implode(",", ['A', 'B']);
                $row['stats'] = implode(",",[
                    'hp' => $dataPokemons[$i][6],
                    'attack' => $dataPokemons[$i+1][6],
                    'defense' => $dataPokemons[$i+2][6],
                    'max_cp' => $dataPokemons[$i+3][6],
                    'max_buddy_cp' => $dataPokemons[$i+4][6],
                ]);
                array_push($row['fast_attacks'],$dataPokemons[$i][7]);
                array_push($row['special_attacks'],$dataPokemons[$i][8]);
            }else{
                if(isset($dataPokemons[$i][7])){
                    array_push($row['fast_attacks'],$dataPokemons[$i][7]);
                }
                if(isset($dataPokemons[$i][8])){
                    array_push($row['special_attacks'],$dataPokemons[$i][8]);
                }
            }

            if(isset($dataPokemons[$i+1])){
                if($dataPokemons[$i+1][0] != "")
                {
                    $row['fast_attacks'] = implode(",", $row['fast_attacks']);
                    $row['special_attacks'] = implode(",", $row['special_attacks']);
                    $data[$id] = $row;
                    $row['fast_attacks'] = $row['special_attacks'] = [];
                }
            }else{
                $row['fast_attacks'] = implode(",", $row['fast_attacks']);
                $row['special_attacks'] = implode(",", $row['special_attacks']);
                $data[$id] = $row;
                $row['fast_attacks'] = $row['special_attacks'] = [];
            }
        }
        Pokemon::insert($data);
    }
}
