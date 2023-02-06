<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use PDF;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $data = Employee::where('nama', 'LIKE', '%' . $request->search . '%')->paginate(4);
        } else {
            $data = Employee::paginate(4);
        }
        return view('pegawai/datapegawai', compact('data'));
    }

    public function tambahpegawai()
    {
        return view('pegawai/tambahdata');
    }
    public function insertdata(Request $request)
    {
        // dd($request->all());
        $data = Employee::create($request->all());
        if ($request->hasFile('foto')) {
            $request->file('foto')->move('fotopegawai/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }
        return redirect()->route('pegawai')->with('success', 'Berhasil menambahkan data');
    }

    public function tampildata($id)
    {
        $data = Employee::find($id);
        return view('pegawai/tampildata', compact('data'));
    }


    public function updatedata(Request $request, $id)
    {
        $data = Employee::find($id);
        $data->update($request->all());
        return redirect()->route('pegawai')->with('success', 'Berhasil mengupdate data');
    }
    public function deletedata($id)
    {
        $data = Employee::find($id);
        $data->delete();
        return redirect()->route('pegawai')->with('success', 'Berhasil menghapus data');
    }
    public function exportPdf()
    {
        $data = Employee::all();
        view()->share('data', $data);
        $pdf = PDF::loadview('pegawai/datapegawai-pdf');
        return $pdf->download('data.pdf');
    }
}
