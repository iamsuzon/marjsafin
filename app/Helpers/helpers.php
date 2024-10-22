<?php

use Illuminate\Support\Facades\Route;

function countryList(): array
{
    return [
        'kuwait' => 'Kuwait',
        'oman' => 'Oman',
        'saudi_arabia' => 'Saudi Arabia',
    ];
}

function centerList(): array
{
    return [
        'mohamid_medical_center' => 'Mohamid medical center - notun bazar 100 feet',
        'nazrul_islam_diagnostic_medical_center' => 'Nazrul Islam Diagnostic medical center - Maradia bazar banasary Rampura',
        'reda_hamza_diagnostic' => 'Reda Hamza Diagnostic - Padour bazar bishwa road cumilla',
        'turkey_medical_services' => 'Turkey Medical Services - Chattogram',
    ];
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
    ];
}

function nationality(): array
{
    return [
        'bangladeshi' => 'Bangladeshi',
        'indian' => 'Indian',
        'pakistani' => 'Pakistani',
        'nepali' => 'Nepali',
        'bhutanese' => 'Bhutanese',
        'srilankan' => 'Srilankan',
    ];
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
