<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CardManageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $card = Cards::select('id','user_id','card_holder_name', 'masked_card_number')
            ->where('user_id', $user->id)
            ->first();

        return view('user.card-manage.index', [
            'card_holder_name' => $card->card_holder_name ?? '',
            'card_number' => $card->masked_card_number ?? ''
        ]);
    }

    public function storeCard(Request $request)
    {
        $validatedData = $request->validate([
            'card_holder_name' => 'required',
            'card_number' => 'required|numeric',
            'card_expiry_date' => 'required',
            'card_security_code' => 'required|numeric',
        ]);

        $card_holder_name = $request->input('card_holder_name');
        $card_number = Crypt::encryptString($validatedData['card_number']);
        $card_expiry_date = Crypt::encryptString($validatedData['card_expiry_date']);
        $card_security_code = Crypt::encryptString($validatedData['card_security_code']);

        $masked = str_repeat('*', strlen($validatedData['card_number']) - 4) . substr($validatedData['card_number'], -4);

        $user = auth()->user();

        $card = Cards::updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'user_id' => $user->id,
                'user_type' => 1, // assume 1 will be for user
                'card_holder_name' => $card_holder_name,
                'card_number' => $card_number,
                'masked_card_number' => $masked,
                'card_expiration_date' => $card_expiry_date,
                'card_cvv' => $card_security_code,
                'access_key' => config('app.key')
            ]
        );

        if ($card) {
            return response()->json([
                'status' => true,
                'message' => 'Card Added Successfully',
                'card_holder_name' => $card_holder_name,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
        ]);
    }
}
