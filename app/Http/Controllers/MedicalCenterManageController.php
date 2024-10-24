<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MedicalCenterManageController extends Controller
{
    public function newMedicalCenter()
    {
        return view('admin.new-medical-center');
    }

    public function newMedicalCenterCreate(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:medical_centers',
            'name' => 'required',
            'email' => 'nullable|email|unique:medical_centers',
            'password' => 'required',
            'address' => 'nullable',
        ]);

        MedicalCenter::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'],
        ]);

        return back()->with('success', 'Medical Center created successfully.');
    }

    public function MedicalCenterList()
    {
        $medicalCenterList = MedicalCenter::all();
        return view('admin.medical-center-list', compact('medicalCenterList'));
    }

    public function MedicalCenterChangePassword(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'password' => 'required|confirmed',
        ]);

        $medicalCenter = MedicalCenter::find($validated['id']);
        $medicalCenter->password = Hash::make($validated['password']);
        $medicalCenter->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.'
        ]);
    }

    public function MedicalCenterUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'nullable|email',
            'address' => 'nullable',
        ]);

        $medicalCenter = MedicalCenter::find($validated['id']);
        $medicalCenter->name = $validated['name'];
        $medicalCenter->email = $validated['email'];
        $medicalCenter->address = $validated['address'];
        $medicalCenter->save();

        return response()->json([
            'status' => true,
            'message' => 'Medical Center updated successfully.'
        ]);
    }

    public function MedicalCenterDelete()
    {
        $validated = request()->validate([
            'id' => 'required',
        ]);

        $medicalCenter = MedicalCenter::find($validated['id']);
        $medicalCenter->delete();

        return response()->json([
            'status' => true,
            'message' => 'Medical Center deleted successfully.'
        ]);
    }
}
