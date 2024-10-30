<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BanUserManageController extends Controller
{
    public function banUser($id)
    {
        abort_if(! auth()->user()->hasRole('super-admin'), 403);

        $user = User::findOrFail($id);

        if ($user->banned)
        {
            $user->banned()->delete();
            $status = 'unbanned';
        } else {
            $user->banned()->create([
                'user_id' => $user->id,
                'banned_at' => now(),
            ]);
            $status = 'banned';
        }

        return redirect()->back()->with('success', 'User has been ' . $status . ' successfully.');
    }
}
