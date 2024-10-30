<?php

namespace App\Http\Controllers;

use App\Models\StaticOption;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
    public function generalSettings()
    {
        $adText = StaticOption::getOption('ad_text');
        $showNotice = StaticOption::getOption('show_notice');
        $noticeText = StaticOption::getOption('notice_text');

        return view('admin.general-settings', [
            'adText' => $adText,
            'showNotice' => $showNotice,
            'noticeText' => $noticeText,
        ]);
    }

    public function generalSettingsUpdate(Request $request)
    {
        $validated = $request->validate([
            'ad_text' => 'required|string|max:255',
            'show_notice' => 'nullable|string',
            'notice_text' => 'nullable|string',
        ]);

        StaticOption::setOption('ad_text', $validated['ad_text']);
        StaticOption::setOption('show_notice', $validated['show_notice'] ?? '');
        StaticOption::setOption('notice_text', $validated['notice_text'] ?? '');

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }
}
