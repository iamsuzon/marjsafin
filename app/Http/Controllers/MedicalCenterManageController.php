<?php

namespace App\Http\Controllers;

use App\Models\AllocateCenter;
use App\Models\MedicalCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

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

    public function AllocateCenterList()
    {
        $allocationCenterList = AllocateCenter::all();
        return view('admin.allocation-center-list', compact('allocationCenterList'));
    }

    public function newAllocateCenter(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $slug = Str::slug($validated['name']);
        $allocateCenter = AllocateCenter::where('slug', $slug)->first();
        if ($allocateCenter) {
            return response()->json([
                'status' => false,
                'message' => 'Allocate center already exists.'
            ]);
        }

        AllocateCenter::create([
            'name' => $validated['name'],
            'slug' => $slug
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Allocate center created successfully.'
        ]);
    }

    public function updateAllocateCenter(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
        ]);

        $allocateCenter = AllocateCenter::find($validated['id']);
        $allocateCenter->name = $validated['name'];
        $allocateCenter->save();

        return response()->json([
            'status' => true,
            'message' => 'Allocate center updated successfully.'
        ]);
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
