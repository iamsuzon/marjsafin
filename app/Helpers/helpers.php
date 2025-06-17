<?php

use App\Models\AllocateCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use App\Models\SlipMedicalCenterRate;
use Illuminate\Support\Facades\Route;

function countryList(): array
{
    return [
//        'kuwait' => 'Kuwait',
//        'oman' => 'Oman',
        'saudi_arabia' => 'Saudi Arabia',
    ];
}

function slipCenterList(): array
{
    return [
        '2031' => [
            'title' => 'Barishal',
            'slug' => 'barishal',
            'list' => [
                'alif-check-up-services' => 'Alif Check-up Services',
                'barishal-central-diagnostic-center' => 'Barishal Central Diagnostic Center',
                'yaqeen-medical-centre' => 'Yaqeen Medical Centre',
            ],
        ],
        '2061' => [
            'title' => 'Chandpur',
            'slug' => 'chandpur',
            'list' => []
        ],
        '81' => [
            'title' => 'Chitagong',
            'slug' => 'chitagong',
            'list' => [
                'aks-khan-diagnostics-ltd' => 'AKS Khan Diagnostics Ltd',
                'ali-shah-diagnostic-specialish-doctors-consultation-centre' => 'Ali Shah Diagnostic & Specialish Doctors Consultation Centre',
                'apex-diagnostic-services-pvt-ltd' => 'Apex Diagnostic Services (pvt) Ltd.',
                'check-up-diagnostic-centre' => 'Check Up Diagnostic Centre',
                'green-crescent-medical-diagnostic' => 'Green Crescent Medical Diagnostic',
                'human-diagnostic-and-medical-check-up-center' => 'Human Diagnostic and Medical Check up Center',
                'medical-point-diagnostic-center' => 'Medical Point Diagnostic Center',
                'medicare-medical-check-up-centre' => 'Medicare Medical Check-up Centre',
                'sunway-medical-centre' => 'Sunway Medical Centre',
                'the-urbane-clinical-services' => 'The Urbane Clinical Services',
                'turkey-medical-services' => 'Turkey Medical Services'
            ]
        ],
        '2033' => [
            'title' => 'Cox\'s Bazar',
            'slug' => 'cox-s-bazar',
            'list' => [
                'al-man-medical-center' => 'Al Man Medical Center',
                'bay-medical-centre' => 'Bay Medical Centre',
                'chemon-health-care' => 'Chemon Health Care',
                'max-medical-center' => 'Max medical Center',
                'shouq-medical-point' => 'Shouq Medical Point',
            ]
        ],
        '2032' => [
            'title' => 'Cumilla',
            'slug' => 'cumilla',
            'list' => [
                'jeeshan-check-up-services' => 'Jeeshan Check-up Services',
                'medi-check-medical-service-limited' => 'Medi Check Medical Service Limited',
                'medi-health-medical-center' => 'Medi Health Medical Center',
                'reda-hamzah-diagnostic-center' => 'Reda Hamzah Diagnostic Center',
                'tabuk-medical-center' => 'Tabuk Medical Center',
                'trust-medical-diagnostic-center' => 'Trust Medical & Diagnostic Center',
                'qortoba-medical-center' => 'Qortoba Medical Center',
                'afb-medical-checkup-center' =>  'AFB MEDICAL CHECKUP CENTRE',
            ]
        ],
        '80' => [
            'title' => 'Dhaka',
            'slug' => 'dhaka',
            'list' => [
                'advance-health-care' => 'ADVANCE HEALTH CARE',
                'al-arouba-medical-services-p-ltd' => 'Al Arouba Medical Services P.Ltd',
                'al-hayatt-medical-centre' => 'Al Hayatt Medical Centre',
                'al-jami-diagnostic-centre' => 'Al Jami Diagnostic Centre',
                'al-mashoor-diagnostic-services' => 'Al Mashoor Diagnostic Services',
                'al-mubasher-medical-diagnostic-services' => 'Al Mubasher Medical Diagnostic Services',
                'al-baha-medical-center' => 'AL-BAHA MEDICAL CENTER',
                'albustan-medical-center' => 'Albustan Medical Center',
                'al-humyra-health-centre-ltd' => 'Al-Humyra Health Centre Ltd',
                'alif-lam-mim-health-services-ltd' => 'ALIF-LAM-MIM HEALTH SERVICES LTD',
                'allied-diagnostics-ltd' => 'Allied Diagnostics Ltd',
                'al-madina-medical-services' => 'Al-Madina Medical Services',
                'al-nahda-medical-centre' => 'Al-Nahda Medical Centre',
                'al-riyadh-medical-check-up' => 'Al-Riyadh Medical Check Up',
                'altashkhis-markaz-limited' => 'ALTASHKHIS MARKAZ LIMITED',
                'amir-jahan-medical-center' => 'AMIR JAHAN MEDICAL CENTER',
                'anas-medical-center' => 'ANAS MEDICAL CENTER',
                'arabian-medical-center' => 'Arabian Medical Center',
                'bashundhara-medical-center' => 'Bashundhara Medical Center',
                'chandshi-medical-center' => 'Chandshi Medical Center',
                'confidence-medical-centre' => 'Confidence Medical Centre',
                'crystal-diagnostic' => 'Crystal Diagnostic',
                'dawa-medical-centre' => 'DAWA MEDICAL CENTRE',
                'dhaka-crown-medical-centre' => 'Dhaka Crown Medical Centre',
                'evergreen-medical-center' => 'Evergreen Medical Center',
                'fairways-medical-center' => 'Fairways Medical Center',
                'global-medicare-diagnostics-ltd' => 'Global Medicare Diagnostics Ltd.',
                'green-crescent-health-services' => 'Green Crescent Health Services',
                'gulf-medical-center' => 'Gulf Medical Center',
                'gulshan-medicare-dhaka' => 'Gulshan Medicare - Dhaka',
                'health-care-center' => 'Health Care Center',
                'healthcare-diagnostic-center-ltd' => 'Healthcare Diagnostic Center Ltd',
                'ibn-omar-medical-and-diagnostic-center' => 'IBN OMAR MEDICAL AND DIAGNOSTIC CENTER',
                'ibn-rushd-medical-center' => 'Ibn Rushd Medical Center',
                'ibn-sina-medical-check-up-unit' => 'Ibn Sina Medical Check Up Unit',
                'index-diagnostic-center' => 'INDEX DIAGNOSTIC CENTER',
                'international-health-center' => 'International Health Center',
                'ishtiyaq-medical-center' => 'Ishtiyaq Medical Center',
                'kent-medical-services-ltd' => 'Kent Medical Services Ltd.',
                'khoulud-medical-check-up' => 'Khoulud Medical Check-up',
                'lab-quest-limited' => 'LAB QUEST LIMITED',
                'leading-health-check-up' => 'Leading Health Check up',
                'life-diagnostic-center' => 'Life Diagnostic Center',
                'makkha-medical-center' => 'Makkha Medical Center',
                'malancha-medical-services-ltd' => 'Malancha Medical Services Ltd.',
                'mediline-health-management-ltd' => 'MEDILINE HEALTH MANAGEMENT LTD',
                'medinova-medical-services-ltd' => 'Medinova Medical Services Ltd.',
                'mediquest-diagnostics-ltd' => 'Mediquest Diagnostics Ltd.',
                'meditest-medical-services' => 'MediTest Medical Services',
                'mohaimid-medical-center' => 'Mohaimid Medical Center',
                'mohammdi-healthcare-systems-pvt-ltd.' => 'Mohammdi Healthcare Systems Pvt. Ltd.',
                'moon-check-up-opc' => 'Moon Check-up OPC',
                'mostafa-health-care' => 'Mostafa Health Care',
                'muscat-medical-center' => 'Muscat Medical Center',
                'nafa-medical-centre' => 'NAFA MEDICAL CENTRE',
                'namirah-medical-center' => 'Namirah Medical Center',
                'national-medical-center-limited' => 'National Medical Center Limited',
                'nazrul-islam-diagnostic' => 'Nazrul Islam Diagnostic',
                'nova-medical-center' => 'Nova Medical Center',
                'orbitals-medical-centre-limited' => 'Orbitals Medical Centre Limited',
                'overseas-health-checkup-ltd' => 'Overseas Health Checkup Ltd.',
                'pacific-medical-diagnostic-center' => 'PACIFIC MEDICAL & DIAGNOSTIC CENTER',
                'paradyne-medical-centre' => 'Paradyne Medical Centre',
                'paramount-medical-centre' => 'Paramount Medical Centre',
                'perlov-medical-services-ltd' => 'Perlov Medical Services Ltd.',
                'phoenix-medical-center' => 'PHOENIX MEDICAL CENTER',
                'praava-health' => 'Praava Health',
                'pulse-medical-center' => 'Pulse Medical Center',
                'pushpo-clinic' => 'Pushpo Clinic',
                'quest-medical-centre' => 'Quest Medical Centre',
                'relyon-medicare' => 'RELYON MEDICARE',
                'rx-medical-centre' => 'Rx Medical Centre',
                'saadiq-medical-services-ltd' => 'Saadiq Medical Services Ltd.',
                'saam-health-checkup-ltd' => 'Saam Health Checkup Ltd',
                'safa-diagnostic-center' => 'Safa Diagnostic Center',
                'sahara-medical-center' => 'Sahara Medical Center',
                'saimon-medical-centre' => 'Saimon Medical Centre',
                'sara-medical-center' => 'SARA MEDICAL CENTER',
                'sarvoshrestha-medical-center' => 'SARVOSHRESTHA MEDICAL CENTER',
                'saudi-bangladesh-services-company' => 'Saudi Bangladesh Services Company',
                'skn-health-services' => 'SKN Health Services',
                'smart-medical-centre' => 'Smart Medical Centre',
                'standard-medical-centre' => 'Standard Medical Centre',
                'star-medical-and-diagnostic-center' => 'STAR MEDICAL AND DIAGNOSTIC CENTER',
                'the-classic-medical-centre-ltd' => 'The Classic Medical Centre Ltd.',
                'the-eureka-diagnostic-medical-services' => 'The Eureka Diagnostic & Medical Services',
                'transworld-medical-center' => 'Transworld Medical Center',
                'tulip-medical-center' => 'Tulip Medical Center',
                'unique-medical-centre' => 'Unique Medical Centre',
                'world-horizon-medical-services-ltd' => 'WORLD HORIZON MEDICAL SERVICES LTD',
                'yadan-medical' => 'YADAN MEDICAL',
                'zain-medical-limited' => 'Zain Medical Limited',

                // Added on 29-apr-2025
                'al-mahmud-medical-center' => 'AL MAHMUD MEDICAL CENTER',
                'al-maktoum-health-care' => 'Al Maktoum Health Care',
                'al-qassimi-health-care' => 'Al Qassimi Health Care',
                'al-tawaf-medical-center' => 'AL TAWAF MEDICAL CENTER',
                'al-barakah-medical-centre-limited' => 'AL-BARAKAH MEDICAL CENTRE LIMITED',
                'aline-medical-services-ltd' => 'Aline Medical Services Ltd.',
                'ammar-medical-center' => 'AMMAR MEDICAL CENTER',
                'aoc-medical-center' => 'AOC Medical Center',
                'ayaz-medical-center' => 'AYAZ MEDICAL CENTER',
                'azwa-medical-centre' => 'AZWA MEDICAL CENTRE',
                'bms-medical-checkup-centre' => 'BMS Medical Checkup Centre',
                'carbon-medical-center' => 'CARBON MEDICAL CENTER',
                'catharsis-medical-centre-limited' => 'Catharsis Medical Centre Limited',
                'central-health-checkup' => 'Central Health Checkup',
                'city-lab' => 'City Lab',
                'city-medical-centre' => 'City Medical Centre',
                'dynamic-lab' => 'Dynamic Lab',
                'fortune-medical-centre' => 'Fortune Medical Centre',
                'future-medical' => 'FUTURE MEDICAL',
                'greenland-medical-center-limited' => 'Greenland Medical Center Limited',
                'hasan-medical-service-ltd' => 'HASAN MEDICAL SERVICE LTD.',
                'icon-medical-centre' => 'ICON MEDICAL CENTRE',
                'indigo-healthcare-ltd' => 'Indigo Healthcare Ltd.',
                'islamia-diagnostic-center' => 'Islamia Diagnostic Center',
                'jg-healthcare-limited' => 'JG HEALTHCARE LIMITED',
                'kgn-medicare-limited' => 'KGN Medicare Limited',
                'lab-science-diagnostic' => 'LAB SCIENCE DIAGNOSTIC',
                'lamia-medical-health-checkup-centre' => 'LAMIA MEDICAL HEALTH CHECKUP CENTRE',
                'lifeline-medical-centre' => 'Lifeline Medical Centre',
                'life-scan-medical-and-diagnostic-center' => 'LIFE-SCAN MEDICAL AND DIAGNOSTIC CENTER',
                'lotus-medical-centre' => 'Lotus Medical Centre',
                'm-rahman-medical-diagnostic-center' => 'M. RAHMAN MEDICAL & DIAGNOSTIC CENTER',
                'medifly-health-services' => 'Medifly Health Services',
                'medinet-medical-services' => 'Medinet Medical Services',
                'medipath-medical-diagnostic-center' => 'MediPath Medical & Diagnostic Center',
                'medison-medical-services-limited' => 'Medison Medical Services Limited',
                'meem-medical-center' => 'MEEM MEDICAL CENTER',
                'micare-health-limited' => 'MICARE HEALTH LIMITED',
                'modern-medical-center' => 'MODERN MEDICAL CENTER',
                'mohammdi-healthcare-systems-pvt-ltd' => 'Mohammdi Healthcare Systems Pvt. Ltd.',
                'musa-health-checkup-centre' => 'MUSA HEALTH CHECKUP CENTRE',
                'mysa-medical-services-pvt-ltd' => 'MYSA MEDICAL SERVICES (PVT) LTD',
                'new-karama-medical-services' => 'NEW KARAMA MEDICAL SERVICES',
                'on-care-diagnostic-centre' => 'On Care Diagnostic Centre',
                'perfect-medicare-ltd' => 'PERFECT MEDICARE LTD.',
                'precision-diagnostics-ltd' => 'Precision Diagnostics Ltd',
                'prime-medical-centre' => 'Prime Medical Centre',
                'sr-medical-diagnostic-center' => 'SR Medical & Diagnostic Center',
                'true-health-medical-and-diagnostic-center' => 'True Health Medical And Diagnostic Center',
                'unicare-medical-services' => 'UNICARE MEDICAL SERVICES',
                'union-health-center' => 'Union Health Center',
                'unity-medical-checkup-centre' => 'UNITY MEDICAL CHECKUP CENTRE',
                'uttara-crescent-clinic-pvt-ltd' => 'UTTARA CRESCENT CLINIC (PVT) LTD',
                'vita-health-medical-center' => 'Vita Health Medical Center',
                'zam-zam-medical-center' => 'ZAM ZAM MEDICAL CENTER',
                'zara-health-care' => 'Zara Health Care',
            ]
        ],
        '2030' => [
            'title' => 'Rajshahi',
            'slug' => 'rajshahi',
            'list' => [
                'al-ali-diagnostic-center' => 'Al Ali Diagnostic Center',
                'al-nahiyan-diagnostic-medical-services' => 'Al- NahIyan Diagnostic & medical Services',
                'hope-medical-centre' => 'Hope Medical Centre',
                'labaid-limited-diagnostics-rajshahi' => 'Labaid Limited (Diagnostics), Rajshahi',
                'nazowa-medical-centre' => 'Nazowa Medical Centre',
                'satata-medical-checkup-center' => 'Satata medical Checkup Center',
            ]
        ],
        '2059' => [
            'title' => 'Sherpur',
            'slug' => 'sherpur',
            'list' => []
        ],
        '83' => [
            'title' => 'Sylhet',
            'slug' => 'sylhet',
            'list' => [
                'a-rahman-medical-center' => 'A. Rahman Medical Center',
                'abc-diagnostic-center' => 'ABC Diagnostic Center',
                'al-hamad-medical-centre-ltd' => 'Al-Hamad Medical Centre Ltd.',
                'al-khaled-medical-services' => 'Al-Khaled Medical Services',
                'green-crescent-medilab' => 'Green Crescent Medilab',
                'healthfirst-medical-centre' => 'Healthfirst Medical Centre',
                'j-b-medical-center-ltd' => 'J B Medical Center Ltd.',
                'medinova-medical-services-ltd' => 'Medinova Medical Services',
                'sylhet-city-medical-centre' => 'Sylhet City Medical Centre',
            ]
        ]
    ];
}

function slipCenterListWithRate(): array
{
    $slipRates = SlipMedicalCenterRate::get(['rate', 'medical_center_slug', 'discount'])->toArray();
    $slipMedicalCenters = slipCenterList();

    foreach ($slipMedicalCenters as $key => $center) {
        foreach ($center['list'] as $index => $item) {
            $rateObj = collect($slipRates)->where('medical_center_slug', $index)->first();
            $slipMedicalCenters[$key]['rates'][$index] = [
                'center_slug' => $index,
                'rate' => $rateObj['rate'] ?? 0,
                'discount' => $rateObj['discount'] ?? 0
            ];
        }
    }

    return $slipMedicalCenters;
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

function slipType(): array
{
    return [
        'normal' => 'Normal Slip',
        'special' => 'Special Slip'
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

function problemList(): array
{
    return [
        'hbs' => 'HBS',
        'tph' => 'TPH',
        'vdrl' => 'VDRL',
        'tph vdrl' => 'TPH VDRL',
        'tb' => 'TB',
        'pt' => 'PT'
    ];
}

function paymentMethods(): array
{
    return [
        'ibbl' => 'IBBL',
        'brac' => 'Brac',
        'eastern_bank' => 'Eastern Bank',
        'dbbl' => 'DBBL'
    ];
}

function unionAccountTypes(): array
{
    return [
        'user' => 'User',
        'medical_center' => 'Medical Center',
    ];
}

function healthConditions(): array
{
    return [
        "fit" => "Fit",
        "cfit" => "C.Fit",
        "unfit" => "Unfit",
        "held-up" => "Held-Up",
    ];
}

function medicalStatus(): array
{
    return [
        'new' => 'New',
        'in-progress' => 'In Progress',
        'under-review' => 'Under Review',
        'fit' => 'Fit',
        'slip-not-found' => 'Slip Not Found',
    ];
}

function getMedicalStatusName($status): string
{
    return medicalStatus()[$status] ?? '';
}

function getHealthConditionsName($condition): string
{
    return healthConditions()[$condition] ?? '';
}

function getPaymentMethodName($method): string
{
    return paymentMethods()[$method] ?? 'admin';
}

function getAllocatedMedicalCenterName(Application $item): string
{
    return $item->allocatedMedicalCenter?->allocated_medical_center ?? '';
}

function getAllocatedMedicalCenterHumanName(Application $item): string
{
    $text = str_replace('-',' ', $item->allocatedMedicalCenter?->allocated_medical_center) ?? '';
    return ucwords($text);
}

function activeCurrentSidebarMenu(string|array $routeName): string
{
    if (is_array($routeName)) {
        return in_array(Route::currentRouteName(), $routeName) ? 'active-menu' : '';
    }

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
    if ($location === 'mostafa_health') {
        $prefix = 'IMS-MHC-';
    }
//    elseif ($location === 'kent_medical') {
//        $prefix = 'Kent-x02-';
//    }
//    elseif ($location === 'malancha_medical') {
//        $prefix = 'Malancha-x02-';
//    }
    elseif ($location === 'malancha_medical') { // Changed at 04-Jan-2025
        $prefix = 'Malancha-J83-';
    }
    elseif ($location === 'perlov_Medical' || $location === 'Medinova_Medical' || $location === 'Saadiq_Medical' || $location === 'kent_medical') {
        $prefix = 'J83-';
    }
    elseif ($location === 'advance_medical') {
        $prefix = 'ADVM-'.now()->format('d-m-y').'-S-';
    }

    // Count how many entries already exist with this location prefix
    $count = Application::where('center_name', $location)->count();

    // Increment count for the new entry, and pad with leading zeros
    $newNumber = str_pad($count + 1, 2, '0', STR_PAD_LEFT);

    // Return the full code
    return $prefix . $newNumber;
}

function fixNoticeText($text): string
{
    return str_replace('@gap', '<br>', $text);
}

function amountWithCurrency($amount): string
{
    return number_format($amount, 2) . ' BDT';
}

function activeCurrentUrl(string $url, string $activeClass = 'active'): string
{
    return url()->current() === $url ? $activeClass : '';
}

function listSerialNumber($applicationList, $loop): int
{
    return ($applicationList->currentPage() - 1) * $applicationList->perPage() + $loop->index + 1;
}

function hasSlipPermission()
{
    $user = auth('web')->user();
    return $user->has_slip_permission;
}

function hasMedicalPermission()
{
    $user = auth('web')->user();
    return $user->has_medical_permission;
}

function hasLinkPermission()
{
    $user = auth('web')->user();
    return $user->has_link_permission;
}

function appointmentBookingCountry(): array
{
    return [
        'BD' => 'Bangladesh',
        'SA'  => 'Saudi Arabia',
        'BH'  => 'Bahrain',
        'KW'  => 'Kuwait',
        'OM'  => 'Oman',
        'QA'  => 'Qatar',
        'UAE' => 'UAE',
        'YEM' => 'Yemen'
    ];
}

function appointmentBookingCity(): array
{
    return [
        80   => 'Dhaka',
        2031 => 'Barishal',
        2061 => 'Chandpur',
        81   => 'Chitagong',
        2033 => "Cox's Bazar",
        2032 => 'Cumilla',
        2030 => 'Rajshahi',
        2059 => 'Sherpur',
        83   => 'Sylhet'
    ];
}

function appointmentBookingNationality(): array
{
    return [
        15 => 'Bangladeshi'
    ];
}

function appointmentBookingVisaType(): array
{
    return [
        'wv' => 'Work Visa',
        'fv' => 'Family Visa'
    ];
}

function appointmentBookingAppliedPosition(): array
{
    return [
        31  => 'Labour',
        18  => 'Banking & Finance',
        19  => 'Carpenter',
        20  => 'Cashier',
        21  => 'Electrician',
        22  => 'Engineer',
        23  => 'General Secretory',
        24  => 'Health & Medicine & Nursing',
        25  => 'Heavy Driver',
        26  => 'IT & Internet Engineer',
        27  => 'Leisure & Tourism',
        28  => 'Light Driver',
        29  => 'Mason',
        30  => 'President',
        32  => 'Plumber',
        33  => 'Doctor',
        34  => 'Family',
        35  => 'Steel Fixer',
        36  => 'Aluminum Technician',
        37  => 'Nurse',
        38  => 'Male Nurse',
        39  => 'Ward Boy',
        40  => 'Shovel Operator',
        41  => 'Dozer Operator',
        42  => 'Car Mechanic',
        43  => 'Petrol Mechanic',
        44  => 'Diesel Mechanic',
        45  => 'Student',
        46  => 'Accountant',
        47  => 'Lab Technician',
        48  => 'Drafts man',
        49  => 'Auto-Cad Operator',
        50  => 'Painter',
        51  => 'Tailor',
        52  => 'Welder',
        53  => 'X-ray Technician',
        54  => 'Lecturer',
        55  => 'A.C Technician',
        56  => 'Business',
        57  => 'Cleaner',
        58  => 'Security Guard',
        59  => 'House Maid',
        60  => 'Manager',
        61  => 'Hospital Cleaning',
        62  => 'Mechanic',
        63  => 'Computer Operator',
        64  => 'House Driver',
        65  => 'Driver',
        66  => 'Cleaning Labour',
        67  => 'Building Electrician',
        68  => 'Salesman',
        69  => 'Plastermason',
        70  => 'Servant',
        71  => 'Barber',
        72  => 'Residence',
        73  => 'Shepherds',
        74  => 'Employment',
        75  => 'Fuel Filler',
        76  => 'Worker',
        77  => 'House Boy',
        78  => 'House Wife',
        79  => 'RCC Fitter',
        80  => 'Clerk',
        81  => 'Microbiologist',
        82  => 'Teacher',
        83  => 'Helper',
        84  => 'Hajj Duty',
        85  => 'Shuttering',
        86  => 'Supervisor',
        87  => 'Medical Specialist',
        88  => 'Office Secretary',
        89  => 'Technician',
        90  => 'Butcher',
        91  => 'Arabic Food Cook',
        92  => 'Agricultural Worker',
        93  => 'Service',
        94  => 'Studio CAD Designer',
        95  => 'Financial Analyst',
        96  => 'Cabin Appearance (AIR LINES)',
        97  => 'Car Washer',
        98  => 'Surveyor',
        99  => 'Electrical Technician',
        100 => 'Waiter',
        103 => 'Nursing helper',
        104 => 'Anesthesia technician',
        105 => '<s>Marvel',
        106 => 'Marvel',
        107 => 'Construction worker',
    ];
}

function appointmentBookingTypes(): array
{
    return [
        'normal' => 'Normal',
        'normal_plus' => 'Normal Plus',
        'special' => 'Special',
        'special_plus' => 'Special Plus'
    ];
}

function readyForPaymentCacheKey(): string
{
    return "APPOINTMENT-ID";
}

function linkCacheKey($user_id, $appointment_id): string
{
    return "USER:{$user_id}::APPOINTMENT-LINK-ID:{$appointment_id}::LINK";
}
