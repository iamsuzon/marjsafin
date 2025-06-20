<?php

namespace App\Services;

use App\Models\AllocateCenter;
use App\Models\AllocateMedicalCenter;
use App\Models\Application;
use App\Models\MedicalCenter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MedicalApplicationAppointmentService
{
    public static function createApplication($appointment_booking): bool
    {
        $link_data = $appointment_booking->links()
            ->whereNotNull('medical_center')
            ->latest('updated_at')
            ->first();

        if ($link_data) {
            $center_name = $link_data->medical_center;
            $center_name = appointmentCenterNameToMedicalCenterName($center_name); // slugified center name
            $center_name = MedicalCenter::where('username', $center_name)->first();

            if ($center_name) {
                $center_name = $center_name->username;

                $medical_type = $appointment_booking->type;
                $passport_number = $appointment_booking->passport_number;
                $gender = $appointment_booking->gender;

                $traveling_to = $appointment_booking->country_traveling_to;
                $traveling_to = appointmentCountryToMedicalCountry($traveling_to);

                $marital_status = $appointment_booking->marital_status;
                $given_name = $appointment_booking->first_name;
                $surname = $appointment_booking->last_name;
                $pp_issue_place = strtolower($appointment_booking->passport_issue_place);

                $profession = $appointment_booking->applied_position;
                $profession = appointmentPositionToMedicalPosition($profession);

                $nationality = 'bangladeshi';

                $date_of_birth = Carbon::parse($appointment_booking->dob);
                $nid_no = $appointment_booking->nid_number;
                $passport_issue_date = Carbon::parse($appointment_booking->passport_issue_date);
                $passport_expiry_date = Carbon::parse($appointment_booking->passport_expiry_date);

                $ref_no = $appointment_booking->reference ?? 'No Reference';

                $user_id = $appointment_booking->user_id;
                $pdf_code = generatePdfCode($center_name);
                $contact_no = 0000;
                $religion = 'none';

                try {
                    Application::create([
                        'user_id' =>  $user_id,
                        'center_name' =>  $center_name,
                        'medical_type' =>  $medical_type,
                        'passport_number' =>  $passport_number,
                        'gender' =>  $gender,
                        'traveling_to' =>   $traveling_to,
                        'marital_status' =>   $marital_status,
                        'given_name' =>   $given_name,
                        'surname' =>    $surname,
                        'pp_issue_place' =>  $pp_issue_place,
                        'profession' =>   $profession,
                        'nationality' =>  $nationality,
                        'date_of_birth' =>   $date_of_birth,
                        'nid_no' =>   $nid_no,
                        'passport_expiry_date' =>   $passport_expiry_date,
                        'passport_issue_date' =>    $passport_issue_date,
                        'ref_no' =>   $ref_no,
                        'contact_no' =>   $contact_no,
                        'pdf_code' =>   $pdf_code,
                        'religion' =>   $religion
                    ]);

                    return true;
                } catch (\Exception $e) {
                    return false;
                }
            }
        }

        return false;
    }
}
