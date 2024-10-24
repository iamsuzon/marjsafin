<?php

namespace App\Http\Controllers;

use App\Models\StaticOption;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
    public function generalSettings()
    {
        $adText = StaticOption::getOption('ad_text');
        return view('admin.general-settings', ['adText' => $adText]);
    }

    public function generalSettingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'ad_text' => 'required|string|max:255',
        ]);

        StaticOption::setOption('ad_text', $validated['ad_text']);

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }
}
