<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PokemonsExport;
use App\Imports\PokemonsImport;
use Maatwebsite\Excel\Facades\Excel;


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
//        Excel::import(new PokemonsImport,request()->file('file'));
        $array = Excel::toArray(new PokemonsImport,request()->file('file'));
        dd($array);
        return back();
    }
}
