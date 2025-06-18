<?php

namespace App\Http\Controllers;

use App\Models\AllocateCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

class MedicalCenterManageController extends Controller
{
    public function newMedicalCenter()
    {
        $centers = allCenterList();
        return view('admin.new-medical-center', ['centers' => $centers]);
    }

    public function newMedicalCenterCreate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:medical_centers,username',
            'email' => 'nullable|email|unique:medical_centers',
            'password' => 'required',
            'address' => 'nullable',
        ]);

        $all_centers = allCenterList();
        if (array_key_exists($validated['name'], $all_centers)) {
            $center_name = $all_centers[$validated['name']];
            $center_slug = $validated['name'];

            MedicalCenter::create([
                'name' => $center_name,
                'username' => $center_slug,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'address' => $validated['address'],
            ]);

            return back()->with('success', 'Medical Center created successfully.');
        }

        return back()->with('error', 'Medical Center creation failed.');
    }

    public function MedicalCenterList()
    {
        $medicalCenterList = MedicalCenter::all();
        return view('admin.medical-center-list', compact('medicalCenterList'));
    }

    public function MedicalCenterListApplication()
    {
        $medicalCenterList = MedicalCenter::with(['applications'])->get();

        foreach ($medicalCenterList as $medicalCenter) {
            $medicalCenter->application_count = Application::where('center_name', $medicalCenter->username)
                ->whereDate('created_at', Carbon::today())
                ->count();
        }

        return view('admin.medical-list-application', compact('medicalCenterList'));
    }

    public function medicalApplicationList()
    {
        \request()->validate([
            'center' => 'required'
        ]);

        $applicationList = Application::with(['applicationPayment', 'applicationCustomComment'])
            ->where('center_name', request('center'));

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date])->get();
        } else if (request()->has('passport_search')) {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $applicationList = $applicationList->where('passport_number', trim(request('passport_search')))->get();
        } else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today())->latest()->get();
        }

        return view('admin.medical-wise-application-list', ['applicationList' => $applicationList, 'username' => request('center')]);
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
            'email' => 'nullable|email',
            'address' => 'nullable',
        ]);

        $medicalCenter = MedicalCenter::find($validated['id']);
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

    public function applicationListPdf()
    {
        $username = \request()->get('center');
        abort_if(empty($username), 404);
        $center = MedicalCenter::where('username', $username)->firstOrFail();
        $center_name = $center->name;

        $applicationList = Application::with(['applicationPayment'])->where('center_name', $username);

        if (\request()->has('start_date') && \request()->has('end_date')) {
            $start_date = \request('start_date');
            $end_date = \request('end_date');

            if ($start_date != null && $end_date != null) {
                abort_if(! auth()->user()->username == 'super_admin', 401);

                $start_date = Carbon::parse($start_date);
                $end_date = Carbon::parse($end_date);

                $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date])->get();
                $pdf_view_name = 'admin.render.application-list-pdf';
            } else {
                return back()->with('error', 'Please select both start and end date.');
            }
        } else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today())->get();
            $pdf_view_name = 'union-account.render.application-list-pdf';
        }

        if ($applicationList->isEmpty()) {
            return back()->with('error', 'No application found.');
        }

        $pdf = \PDF::loadView($pdf_view_name, compact('applicationList', 'center_name'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('application-list.pdf');
    }

    public function medicalApplicationListPdf()
    {
        $applicationList = Application::with(['applicationPayment']);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                $applicationList = $applicationList->whereDate('created_at', Carbon::today());
            } else {
                $start_date = Carbon::parse(request('start_date'));
                $end_date = Carbon::parse(request('end_date'));

                $applicationList = $applicationList->whereBetween('created_at', [$start_date, $end_date]);
            }
        } else {
            $applicationList = $applicationList->whereDate('created_at', Carbon::today());
        }

        $applicationList = $applicationList->latest()->get();
        if ($applicationList->isEmpty()) {
            return back()->with('error', 'No application found.');
        }

        $center_name = 'All Medical Center';

        $pdf = \PDF::loadView('union-account.render.application-list-pdf', compact('applicationList', 'center_name'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('application-list.pdf');
    }
}
