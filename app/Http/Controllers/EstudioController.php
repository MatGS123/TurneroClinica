<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EstudioController extends Controller
{
    public function index()
    {
        // 1. Traemos los estudios con los datos del turno/paciente asociado
        $estudios = Estudio::with('appointment')->latest()->get();
        
        // 2. Traemos todos los turnos para poder listarlos en el select del formulario
        $appointments = Appointment::latest()->get(); 
        
        // 3. Retornamos la vista pasando ambas variables
        return view('backend.estudios.index', compact('estudios', 'appointments'));
    }

    public function store(Request $request)
    {
        // 1. Validamos que los datos cumplan con lo requerido (PDF obligatorio, máx 10MB)
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'nombre_estudio' => 'required|string|max:255',
            'archivo_pdf'    => 'required|mimes:pdf|max:10000', 
        ]);

        // 2. Guardamos el archivo físico en la carpeta 'storage/app/public/estudios'
        $ruta = $request->file('archivo_pdf')->store('estudios', 'public');

        // 3. Guardamos el registro en la base de datos con la ruta del archivo
        Estudio::create([
            'appointment_id' => $request->appointment_id,
            'nombre_estudio' => $request->nombre_estudio,
            'archivo_pdf'    => $ruta,
            'observaciones'  => $request->observaciones,
        ]);

        return redirect()->back()->with('success', 'Estudio subido exitosamente.');
    }

    public function descargar(Estudio $estudio)
    {
        // Retorna el archivo PDF para forzar la descarga directa en el navegador
        return Storage::disk('public')->download($estudio->archivo_pdf, $estudio->nombre_estudio . '.pdf');
    }
}