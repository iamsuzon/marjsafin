<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MedicalCenter;
use App\Models\StaticOption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExcelConverterController extends Controller
{
    private $date;
    public function excelList()
    {
        $list = $this->excelListArray();
        return view('admin.excel-list', compact('list'));
    }

    public function excelDownload(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|in:' . implode(',', array_column($this->excelListArray(), 'key')).',custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $medical_center_username = null;
        $medical_center_id = StaticOption::where('key', 'medical_center_id_for_excel')->first();
        if ($medical_center_id && $medical_center_id->value !== '0') {
            $medical_center = MedicalCenter::find($medical_center_id->value);
            $medical_center_username = $medical_center->username ?? null;
        }

        $applications = Application::select([
            'serial_number',
            'given_name',
            'surname',
            'passport_number',
            'medical_status',
            'health_status_details',
            'center_name'
        ])->where('medical_status', 'fit');

        if ($validated['start_date'] && $validated['end_date']) {
            $start_date = Carbon::parse($validated['start_date']);
            $end_date = Carbon::parse($validated['end_date']);
            $this->date = $start_date->format('d-M-Y') . ' to ' . $end_date->format('d-M-Y');

            $applications = $applications->whereDate('updated_at', '>=', $start_date)
                ->whereDate('updated_at', '<=', $end_date);
        } else {
            $list = $this->excelListArray();
            $data = collect($list)->where('key', $validated['key'])->first();
            $date = Carbon::parse($data['date']);
            $this->date = $date->format('d-M-Y');

            $applications = $applications->whereDate('updated_at', $date);
        }

        if ($medical_center_username) {
            $applications = $applications->where('center_name', $medical_center_username);
        }

        $applications = $applications->get();

        if ($applications->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No data found for this date',
            ]);
        }

        // now convert applications to csv
        $filename = 'report-' . $this->date . '.csv';

        $file = fopen('php://temp', 'w');

        // Add Medical Center Username
        fputcsv($file, [
            $medical_center_username ?? 'all_medical_centers',
            'Advanced Report '. $this->date,
        ]);

        // Add empty row
        fputcsv($file, []);

        // Add CSV headers
        fputcsv($file, [
            'S/N',
            'Reg. No.',
            'Name of Passenger',
            'Passport No.',
            'Status',
            'Cause Of',
        ]);

        // Fetch and process data in chunks
        foreach ($applications as $index => $employee) {
            // Extract data from each employee.
            $data = [
                ++$index,
                $employee->serial_number ?? '',
                $employee->given_name ?? ' ' . $employee->surname ?? '',
                $employee->passport_number ?? '',
                ucfirst($employee->medical_status) ?? '',
                $employee->health_status_details ?? '',
            ];

            // Write data to a CSV file.
            fputcsv($file, $data);
        }

        // Reset the pointer to the beginning of the file
        rewind($file);

        // Get the CSV content
        $csvContent = stream_get_contents($file);

        // Close CSV file handle
        fclose($file);

        // Store or download the file
        $path_to_file = 'temp-csv';
        if (!Storage::exists($path_to_file)) {
            Storage::makeDirectory($path_to_file);
        }
        Storage::put($path_to_file.'/'.$filename, $csvContent); // Save to storage

        return response()->download(storage_path('app/'.$path_to_file.'/'. $filename), $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function excelListArray()
    {
        return [
            [
                'title' => 'Today',
                'key' => 'today',
                'date' => now()->format('d-M-Y'),
            ],
            [
                'title' => 'Yesterday',
                'key' => 'yesterday',
                'date' => now()->subDay()->format('d-M-Y'),
            ],
            [
                'title' => '2 Days Ago',
                'key' => '2-days-ago',
                'date' => now()->subDays(2)->format('d-M-Y'),
            ],
            [
                'title' => '3 Days Ago',
                'key' => '3-days-ago',
                'date' => now()->subDays(3)->format('d-M-Y'),
            ],
            [
                'title' => '4 Days Ago',
                'key' => '4-days-ago',
                'date' => now()->subDays(4)->format('d-M-Y'),
            ],
            [
                'title' => '5 Days Ago',
                'key' => '5-days-ago',
                'date' => now()->subDays(5)->format('d-M-Y'),
            ],
            [
                'title' => '6 Days Ago',
                'key' => '6-days-ago',
                'date' => now()->subDays(6)->format('d-M-Y'),
            ],
            [
                'title' => '7 Days Ago',
                'key' => '7-days-ago',
                'date' => now()->subDays(7)->format('d-M-Y'),
            ]
        ];
    }

    public function setMedicalCenter(Request $request)
    {
        $validated = $request->validate([
            'medical_center_id' => 'required|string'
        ]);

        if ($validated['medical_center_id'] !== '0')
        {
            $medical_center = MedicalCenter::find($validated['medical_center_id']);

            if (!$medical_center) {
                return response()->json([
                    'status' => false,
                    'message' => 'Medical center not found',
                ]);
            }
        }

        StaticOption::updateOrCreate(
            [
                'key' => 'medical_center_id_for_excel'
            ],
            [
                'value' => $validated['medical_center_id']
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Medical center set successfully',
        ]);
    }
}
