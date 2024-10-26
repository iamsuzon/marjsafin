<?php

use App\Models\AllocateCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use Illuminate\Support\Facades\Route;

function countryList(): array
{
    return [
//        'kuwait' => 'Kuwait',
//        'oman' => 'Oman',
        'saudi_arabia' => 'Saudi Arabia',
    ];
}

function centerList(): array
{
    $centerList = MedicalCenter::all();

    $centerListArray = [];
    foreach ($centerList as $center) {
        $centerListArray[$center->username] = $center->name." - ".$center->address;
    }

    return $centerListArray;
}

function ppIssuePlaceList(): array
{
    return [
        'dhaka' => 'Dhaka',
        'cumilla' => 'Cumilla',
        'noakhali' => 'Noakhali',
        'chottogram' => 'Chottogram',
        'sylhet' => 'Sylhet',
        'gazipur' => 'Gazipur',
        'narayanganj' => 'Narayanganj',

    ];
}

function religionList(): array
{
    return [
        'islam' => 'Islam',
        'hinduism' => 'Hinduism',
        'bhuddism' => 'Bhuddism',
        'christianity' => 'Christianity',
        'sikhism' => 'Sikhism',
        'others' => 'Others',
    ];
}

function professionList(): array
{
    return [
        'private_service' => 'Private Service',
        'student' => 'Student',
        'house_worker' => 'House Worker',
        'cleaner' => 'Cleaner',
        'house_maid' => 'House Maid',
        'labour' => 'Labour',
    ];
}

function nationality(): array
{
    return [
        'bangladeshi' => 'Bangladeshi',
//        'indian' => 'Indian',
//        'pakistani' => 'Pakistani',
//        'nepali' => 'Nepali',
//        'bhutanese' => 'Bhutanese',
//        'srilankan' => 'Srilankan',
    ];
}

function medicalType(): array
{
    return [
        'normal' => 'Normal',
        'special' => 'Special'
    ];
}

function allocateMedicalCenter(): array
{
//    return [
//        'barishal' => 'Barishal',
//        'reda_hamza' => 'Reda Hamza',
//        'chemon_health_care' => 'Chemon Health Care',
//    ];

    $centerList = AllocateCenter::all();

    $centerListArray = [];
    foreach ($centerList as $center) {
        $centerListArray[$center->slug] = $center->name;
    }

    return $centerListArray;
}

function getAllocatedMedicalCenterName(Application $item): string
{
    return $item->allocatedMedicalCenter?->allocated_medical_center ?? '';
}

function activeCurrentSidebarMenu($routeName): string
{
    return Route::currentRouteName() === $routeName ? 'active-menu' : '';
}

function travelingToName($text): string
{
    return countryList()[$text] ?? '';
}

function centerName($text): string
{
    return centerList()[$text] ?? '';
}

function customAsset($path): string
{
    return app()->environment('local') ? asset($path) : asset('public/' . $path);
}

function generatePdfCode($location): string
{
    $locationPrefix = ucfirst($location[0]);

    // Define the code prefix with IMS and the location letter
    $prefix = "IMS-{$locationPrefix}-";

    // Count how many entries already exist with this location prefix
    $count = Application::where('center_name', $location)->count();

    // Increment count for the new entry, and pad with leading zeros
    $newNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

    // Return the full code
    return $prefix . $newNumber;
}
