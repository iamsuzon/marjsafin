<?php

namespace App\Http\Controllers;

use App\Models\AllocateCenter;
use App\Models\AllocateMedicalCenter;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DeveloperSettingsController extends Controller
{
    public function upgradeDatabase()
    {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('optimize:clear');

//        $old_application = Application::all()->toArray();
//
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        Application::truncate();
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

//        foreach ($old_application as $application) {
//            unset($application['id']);
//            Application::create($application);
//        }

        Artisan::call('db:seed', ['--force' => true]);

        return back()->with('success', 'Database upgraded successfully.');
    }
}
