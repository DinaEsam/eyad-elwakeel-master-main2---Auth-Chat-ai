<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Patient = User::where('role', 'patient')->get();

        return response()->json($Patient, 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $Patient = User::where('role', 'patient')->find($id);
        if (!$Patient) {
            return response()->json("Patient with this id not found", 404);
        }
        $Patient->delete();
        return response()->json("Patient {$id} deleted", 200);
    }
      /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // البحث عن المريض والتحقق من دوره
        $Patient = User::where('role', 'patient')->find($id);

        if (!$Patient) {
            return response()->json("Patient with this ID not found", 404);
        }

        // التحقق من صحة البيانات
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'sometimes|string|max:20',
            'password' => 'sometimes|min:6',
            'national_id' => 'sometimes|string|max:20',
            'weight' => 'sometimes|numeric|min:1|max:500',
            'height' => 'sometimes|numeric|min:50|max:250',
            'has_chronic_diseases' => 'sometimes|boolean',
            'is_following_with_doctor' => 'sometimes|boolean',
            'diagnosis_date' => 'sometimes|date',
            'disease_stage' => 'sometimes|string|max:50',
        ]);

        // تحديث الحقول المطلوبة فقط
        if ($request->has('name')) {
            $Patient->name = $request->name;
        }
        if ($request->has('email')) {
            $Patient->email = $request->email;
        }
        if ($request->has('phone')) {
            $Patient->phone = $request->phone;
        }
        if ($request->has('password')) {
            $Patient->password = Hash::make($request->password);
        }
        if ($request->has('national_id')) {
            $Patient->national_id = $request->national_id;
        }
        if ($request->has('weight')) {
            $Patient->weight = $request->weight;
        }
        if ($request->has('height')) {
            $Patient->height = $request->height;
        }
        if ($request->has('has_chronic_diseases')) {
            $Patient->has_chronic_diseases = $request->has_chronic_diseases;
        }
        if ($request->has('is_following_with_doctor')) {
            $Patient->is_following_with_doctor = $request->is_following_with_doctor;
        }
        if ($request->has('diagnosis_date')) {
            $Patient->diagnosis_date = $request->diagnosis_date;
        }
        if ($request->has('disease_stage')) {
            $Patient->disease_stage = $request->disease_stage;
        }

        // حفظ التعديلات
        $Patient->save();

        return response()->json([
            'message' => "Patient {$id} updated successfully",
            'patient' => $Patient
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }




}
