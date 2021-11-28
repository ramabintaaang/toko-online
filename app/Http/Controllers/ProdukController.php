<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function allProduk()
    {
        $id = Auth::id();
        $data = DB::select('SELECT * from produk where id_user = ?', [$id]);

        return response()->json($data);
    }

    public function getEditProduk($id)
    {
        $data = Produk::find($id);
        if (is_null($data)) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json($data);
    }

    public function addProduk(Request $request)
    {
        $id = $request->id;
        $id_user = $request->id_user;
        $nama = $request->nama;
        $harga = $request->harga;
        $keterangan = $request->keterangan;
        $stok = $request->stok;

        $data = DB::statement('INSERT INTO produk (id,id_user,nama,harga,keterangan,stok) VALUE (?,?,?,?,?,?)', [$id, $id_user, $nama, $harga, $keterangan, $stok]);
        // $data = Produk::create($request->all());

        // dd($data);
        return response()->json($data);
    }

    public function updateProduk(Request $request)
    {
        $id = $request->id;
        // $id_user = $request->id_user;
        $nama = $request->nama;
        $harga = $request->harga;
        $keterangan = $request->keterangan;
        $stok = $request->stok;

        // DB::update('update produk set name = ? where id = ?', [$name, $id]);
        $data = Produk::where('id', $id)
            ->limit(1)
            ->update([
                'id' => $id,
                'nama' => $nama,
                'harga' => $harga,
                'keterangan' => $keterangan,
                'stok' => $stok
            ]);

        return response()->json([
            'message' => 'berhasil diupdate nih',
            'data' => $data
        ]);
    }

    public function deleteProduk2(Request $request)
    {
        $id = $request->id;
        // $id_user = $request->id_user;
        $nama = $request->nama;
        $harga = $request->harga;
        $keterangan = $request->keterangan;
        $stok = $request->stok;

        // DB::update('update produk set name = ? where id = ?', [$name, $id]);
        $data = Produk::where('id', $id)
            ->limit(1)
            ->delete([
                'id' => $id,
                'nama' => $nama,
                'harga' => $harga,
                'keterangan' => $keterangan,
                'stok' => $stok
            ]);

        return response()->json([
            'message' => 'berhasil dihapus nih',
            'data' => $data
        ]);
    }


    // public function deleteProduk(Request $request, $id)
    // {

    //     $data = Produk::find($id);
    //     if (is_null($data)) {
    //         return response()->json([
    //             'message' => 'data tidak ditemukan',
    //         ], 404);
    //     }
    //     $data->delete($request->all());
    //     return response()->json([
    //         'message' => 'data berhasil dihapus',
    //         'data' => $data
    //     ], 200);
    // }

    public function cari(Request $request)
    {
        $cari = DB::select('select * from produk where id = ?', [$request->satu]);
        return response()->json($cari);
    }


    public function getKeranjang()
    {
        $data = DB::select('SELECT a.* , b.name , c.nama , c.harga ,c.stok  FROM cart a JOIN users b ON a.id_user = b.id JOIN produk c ON a.id_produk = c.id');
        return response()->json($data);
    }
    public function getEditKeranjang($id)
    {
        $data = Cart::find($id);
        if (is_null($data)) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ], 404);
        }
        return response()->json($data);
    }
    public function addKeranjang(Request $request)
    {
        // $id = $request->id;
        $id_user = $request->id_user;
        $id_produk = $request->id_produk;
        $jumlah = $request->jumlah;
        $subtotal = $request->subtotal;

        $data = DB::statement('INSERT INTO cart (id_user,id_produk,jumlah,subtotal) VALUE (?,?,?,?,?)', [$id_user, $id_produk, $jumlah, $subtotal]);
        // $data = Produk::create($request->all());

        // dd($data);
        return response()->json($data);
    }
    public function updateKeranjang(Request $request)
    {
        $jumlah_beli = $request->jumlah_beli;
        $subtotal = $request->subtotal;
        $id = $request->id;
        // $id = $request->id;
        // // $id_user = $request->id_user;
        // $nama = $request->nama;
        // $harga = $request->harga;
        // $keterangan = $request->keterangan;
        // $stok = $request->stok;

        // DB::update('update produk set name = ? where id = ?', [$name, $id]);
        $data = Cart::where('id', $id)
            ->limit(1)
            ->update([
                'id' => $id,
                'jumlah' => $jumlah_beli,
                'subtotal' => $subtotal,
            ]);

        return response()->json([
            'message' => 'berhasil diupdate nih',
            'data' => $data
        ]);
    }
}
