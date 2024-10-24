<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeveloperSettingsController extends Controller
{
    public function upgradeDatabase()
    {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('optimize:clear');

        return back()->with('success', 'Database upgraded successfully.');
    }
}
