<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BanUserManageController extends Controller
{
    public function banUser($id)
    {
        abort_if(!auth()->user()->hasRole('super-admin'), 403);

        $user = User::findOrFail($id);

        if ($user->banned) {
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

    public function deleteUser(Request $request)
    {
        abort_if(!auth()->user()->hasRole('super-admin'), 403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'uid' => 'required|exists:users,id',
        ]);

        abort_if($validated['user_id'] !== $validated['uid'], 403);

        $user = User::with('applications')->findOrFail($validated['user_id']);

//        try {
            if ($user) {
                // use db transaction to delete user and applications and handle rollback
                $user->applications()->delete();

                if (! $user->applications()->exists()) {
                    $user->delete();
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'User has been deleted successfully.',
            ]);
//        } catch (\Exception $exception) {
//            return response()->json([
//                'status' => false,
//                'message' => 'Something went wrong. Please try again later.',
//            ]);
//        }
    }
}
