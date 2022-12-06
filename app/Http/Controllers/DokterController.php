<?php

namespace App\Http\Controllers;

use App\Http\Requests\DokterRequest;
use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokter = Dokter::paginate(10);

        return view('dokters.index',[
            'dokters' => $dokter
        ]);
    }

    public function create()
    {
        return view('dokters.create');
    }

    public function store(DokterRequest $request)
    {
        $data = $request->all();

        // $data['picturePath'] = $request->file('picturePath')->store('assets/dokter', 'public');

        Dokter::create($data);

        return redirect()->route('dokters.index');
    }

    public function edit(Dokter $dokter){
        return view('dokters.edit',[
            'item' => $dokter
        ]);
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->delete();

        return redirect()->route('dokters.index');
    }
}
