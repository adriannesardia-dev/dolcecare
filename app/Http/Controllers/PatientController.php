<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::where('user_id', auth()->id())->latest()->get();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'date_of_birth'  => ['required', 'date'],
            'gender'         => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:255'],
            'address'        => ['required', 'string', 'max:255'],
            'medical_history' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = auth()->id();

        Patient::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Patient record created successfully.',
        ]);
    }

    public function edit($id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'date_of_birth'  => ['required', 'date'],
            'gender'         => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:255'],
            'address'        => ['required', 'string', 'max:255'],
            'medical_history' => ['nullable', 'string'],
        ]);

        $patient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Patient record updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $patient = Patient::where('user_id', auth()->id())->findOrFail($id);
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient record deleted successfully.',
        ]);
    }
}
