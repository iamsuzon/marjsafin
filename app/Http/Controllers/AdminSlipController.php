<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use App\Models\SlipStatusLink;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminSlipController extends Controller
{
    public function slipList()
    {
        abort_if(! auth()->user()->role('super-admin'), 403);

        $start_date = null;
        $end_date = null;

        $slipList = Slip::with(['slipPayment']);

        if (request()->has('start_date') && request()->has('end_date')) {
            if (request('start_date') == null || request('end_date') == null) {
                return back()->with('error', 'Please select both start and end date.');
            }

            $start_date = Carbon::parse(request('start_date'))->format('Y-m-d');
            $end_date = Carbon::parse(request('end_date'))->format('Y-m-d');

            $slipList = $slipList->whereBetween('created_at', [$start_date, $end_date])->paginate(20)->withQueryString();
        }
        else if(request()->has('passport_search'))
        {
            if (request('passport_search') == null) {
                return back()->with('error', 'Please enter passport number.');
            }

            $slipList = $slipList->where('passport_number', trim(request('passport_search')))->paginate(20);
        }
        else {
            $slipList = $slipList->whereDate('created_at', Carbon::today())->latest()->paginate(20);
        }

        return view('admin.slip.slip-list', [
            'slipList' => $slipList,
            'start_date' => $start_date ? Carbon::parse($start_date)->format('d-M-Y') : null,
            'end_date' => $end_date ? Carbon::parse($end_date)->format('d-M-Y') : null,
        ]);
    }

    public function slipUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:slips,id',
            'slip_status' => 'required|in:in-queue,processing,completed',
            'link' => 'required_if:slip_status,completed',
        ]);

        SlipStatusLink::updateOrCreate(
            [
                'slip_id' => $validated['id']
            ],
            [
                'slip_id' => $validated['id'],
                'slip_status' => $validated['slip_status'],
                'link' => $validated['link']
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Slip status updated successfully.'
        ]);
    }

    public function slipListSingle($id)
    {
        $slipList = Slip::with(['slipPayment', 'notification'])->where('id', $id)->get();
        $the_application = $slipList->first();

        if ($the_application->notification) {
            if ($the_application->notification->read_at === null)
            {
                $the_application->notification->update(['read_at' => now()]);
            }
        }

        $username = $the_application->center_slug;

        return view('admin.slip.slip-list-single', compact('slipList', 'username'));
    }
}
