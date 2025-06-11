<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'specialty' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'national_id'=>'required',
        ]);

        // Ensure only admin can create a doctor
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Create the doctor user in 'users' table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor', // Assign role
            'national_id'=>$request->national_id,
            'phone'=>$request->phone,
        ]);

        // Create the doctor record in 'doctors' table
        // $doctor = Doctor::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'specialty' => $request->specialty,
        //     'phone' => $request->phone,
        //     'user_id' => $user->id, // Link user with doctor
        // ]);

        return response()->json([
            'message' => 'Doctor added successfully',
            'user' => $user,
            // 'doctor' => $doctor,
        ], 201);
    }

     // To get all Doctors with pagination
     public function index()
{
    // $doctorsFromDoctorTable = Doctor::all();
    $Doctors = User::where('role', 'doctor')->get();

    // ممكن تعرضهم كقوائم منفصلة
    return response()->json($Doctors, 200);
}
public function destroy(Request $request, $id)
{
    $Doctor = User::where('role', 'doctor')->find($id);
    if (!$Doctor) {
        return response()->json("Doctor with this id not found", 404);
    }
    $Doctor->delete();
    return response()->json("Doctor {$id} deleted", 200);
}
public function update(Request $request, $id)
    {
        // البحث عن الدكتور حسب الـ ID
        $doctor = User::where('id', $id)->where('role', 'doctor')->firstOrFail();

        // التحقق من صحة البيانات المدخلة
        $data = $request->validate([
            'name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'phone' => 'string|max:20',
            'password' => 'nullable|min:6',
            'specialization' => 'string|max:255',
            'experience' => 'integer|min:0',
            'address' => 'string|max:255',
        ]);

        // تحديث كلمة المرور إذا تم إرسالها
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // تحديث بيانات الدكتور
        $doctor->update($data);

        return response()->json([
            'message' => 'Doctor updated successfully',
            'doctor' => $doctor
        ], 200);
    }



}
