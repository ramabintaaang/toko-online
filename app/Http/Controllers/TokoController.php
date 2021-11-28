<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokoController extends Controller
{
    public function index()
    {
        return view('tokosephia.index');
    }

    public function getProduk()
    {
        $data = DB::select('SELECT a.*,b.name FROM produk a LEFT JOIN users b ON a.id_user = b.id');
        return view('tokosephia.index', compact('data'));
    }
}
