<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perjanjian;
use App\Mitra;
use App\Dokumen;
use App\PKS;
use App\SKB;
use Illuminate\Support\Facades\DB as DB;

class PerjanjianController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perjanjians = Perjanjian::orderBy('id_perjanjian', 'DESC')->paginate(10);
        $mitras = Mitra::all();
        $dokumens = Dokumen::all();
        $PKSs = PKS::all();
        $SKBs = SKB::all();
        $warnedTerms = DB::select('SELECT * FROM perjanjian
        WHERE datediff(current_date(), tanggal_akhir) >= -150 AND
            datediff(current_date(), tanggal_akhir) <= 0');
        $expiredTerms = DB::select('SELECT * FROM perjanjian
        WHERE tanggal_akhir < current_date()');
        return view('admin')
            ->with('selectedView', 'viewPerjanjian')
            ->with('perjanjians', $perjanjians)
            ->with('mitras', $mitras)
            ->with('SKBs', $SKBs)
            ->with('PKSs', $PKSs)
            ->with('dokumens', $dokumens)
            ->with('warnedTerms', $warnedTerms)
            ->with('expiredTerms', $expiredTerms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'mitra' => 'required',
            'pihak1' => 'required',
            'pihak2' => 'required',
            'nomorDokumen' => 'required'
        ]);

        //Start Create Perjanjian
        $perjanjian = new Perjanjian();
        $perjanjian->Mitra_id_mitra = $request->input('mitra');
        $perjanjian->pihak_1 = $request->input('pihak1');
        $perjanjian->pihak_2 = $request->input('pihak2');
        $perjanjian->Dokumen_no_dokumen = $request->input('nomorDokumen');
        $perjanjian->tanggal_awal = $request->input('tanggalAwal');
        $perjanjian->tanggal_akhir = $request->input('tanggalAkhir');
        $perjanjian->Aktivitas_SKB_id_aktivitas = $request->input('aktivitasSkb');
        $perjanjian->Aktivitas_PKS_id_aktivitas = $request->input('aktivitasPks');
        // $perjanjian->status = ($endDate - $startDate)/60/60/24;
        $perjanjian->status = 'Coming Soon';
        try {
            $perjanjian->save();
            return redirect('/perjanjian')->with('success', 'Perjanjian Berhasil Ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            $code = $e->errorInfo[1];
            if ($code == '1062') {
                return redirect('/perjanjian')->with('error', 'Perjanjian Gagal Ditambahkan');
            }
        }
        //End Create Perjanjian
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perjanjian = Perjanjian::find($id);
        $mitras = Mitra::all();
        $dokumens = Dokumen::all();
        $PKSs = PKS::all();
        $SKBs = SKB::all();
        $warnedTerms = DB::select('SELECT * FROM perjanjian
        WHERE datediff(current_date(), tanggal_akhir) < 150 AND
            tanggal_akhir > current_date()');
        $expiredTerms = DB::select('SELECT * FROM perjanjian
        WHERE tanggal_akhir < current_date()');
        return view('admin')
            ->with('warnedTerms', $warnedTerms)
            ->with('expiredTerms', $expiredTerms)
            ->with('selectedView', 'updatePerjanjian')
            ->with('perjanjian', $perjanjian)
            ->with('mitras', $mitras)
            ->with('dokumens', $dokumens)
            ->with('SKBs', $SKBs)
            ->with('PKSs', $PKSs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'mitra' => 'required',
            'pihak1' => 'required',
            'pihak2' => 'required',
            'nomorDokumen' => 'required'
        ]);

        //Start Create Perjanjian
        $perjanjian = Perjanjian::find($id);
        $perjanjian->Mitra_id_mitra = $request->input('mitra');
        $perjanjian->pihak_1 = $request->input('pihak1');
        $perjanjian->pihak_2 = $request->input('pihak2');
        $perjanjian->Dokumen_no_dokumen = $request->input('nomorDokumen');
        $perjanjian->tanggal_awal = $request->input('tanggalAwal');
        $perjanjian->tanggal_akhir = $request->input('tanggalAkhir');
        $perjanjian->Aktivitas_SKB_id_aktivitas = $request->input('aktivitasSkb');
        $perjanjian->Aktivitas_PKS_id_aktivitas = $request->input('aktivitasPks');
        // $perjanjian->status = ($endDate - $startDate)/60/60/24;
        $perjanjian->status = 'Coming Soon';
        $perjanjian->save();
        //End Create Perjanjian

        return redirect('/perjanjian')->with('success', 'Perjanjian Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
