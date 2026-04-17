<?php

namespace App\Http\Controllers;

use App\Models\ObraSocial;
use Illuminate\Http\Request;

class ObraSocialController extends Controller
{
    public function index()
    {
        $obrasSociales = ObraSocial::orderBy('nombre')->get();
        return response()->json($obrasSociales);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:obras_sociales,nombre',
        ]);

        $obra = ObraSocial::create(['nombre' => $request->nombre]);

        return response()->json([
            'success' => true,
            'obra' => $obra,
            'message' => 'Obra social creada correctamente.'
        ]);
    }

    public function update(Request $request, ObraSocial $obraSocial)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:obras_sociales,nombre,' . $obraSocial->id,
        ]);

        $obraSocial->update(['nombre' => $request->nombre]);

        return response()->json([
            'success' => true,
            'obra' => $obraSocial,
            'message' => 'Obra social actualizada correctamente.'
        ]);
    }

    public function destroy(ObraSocial $obraSocial)
    {
        $obraSocial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Obra social eliminada correctamente.'
        ]);
    }
}
