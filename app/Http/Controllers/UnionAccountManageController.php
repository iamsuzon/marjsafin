<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenter;
use App\Models\UnionAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UnionAccountManageController extends Controller
{
    public function index()
    {
        $accounts = UnionAccount::all();
        return view('admin.union-list', ['accounts' => $accounts]);
    }

    public function create()
    {
        return view('admin.new-union');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:union_accounts',
            'password' => 'required',
            'email' => 'required',
            'union_account_type' => 'required|in:'.implode(',', array_keys(unionAccountTypes()))
        ]);

        $account = new UnionAccount();
        $account->name = $validated['name'];
        $account->username = $validated['username'];
        $account->password = Hash::make($validated['password']);
        $account->email = $validated['email'];
        $account->account_type = $validated['union_account_type'];
        $account->save();

        $type = str_replace('_', ' ', $account->account_type);

        return back()->with('success', "Union {$type} account created successfully");
    }

    public function edit($id)
    {
        $account = UnionAccount::findOrFail($id);
        return view('admin.edit-union', ['account' => $account]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:union_accounts,username,'.$id,
            'email' => 'required',
            'password' => 'nullable',
        ]);

        abort_if( empty($id), 403);

        $account = UnionAccount::findOrFail($id);
        $account->name = $validated['name'];
        $account->username = $validated['username'];
        $account->email = $validated['email'];
        if (!empty($request->password)) {
            $account->password = Hash::make($validated['password']);
        }
        $account->save();

        $type = str_replace('_', ' ', $account->account_type);

        return back()->with('success', "Union {$type} account updated successfully");
    }

    public function assign($id)
    {
        $account = UnionAccount::with(['unionMedicalCenterList','unionUserList'])->findOrFail($id);

        $sub_accounts = [];
        if ($account->account_type === 'medical_center') {
            $sub_accounts = MedicalCenter::select(['id', 'name', 'username'])->get();

            if ($account->unionMedicalCenterList->isEmpty()) {
                $account->sub_accounts = [];
            }
            else {
                $account->sub_accounts = $account->unionMedicalCenterList->pluck('medical_center_id')->toArray();
            }
        }
        else if ($account->account_type === 'user') {
            $sub_accounts = User::select(['id', 'name', 'username'])->get();

            if ($account->unionUserList->isEmpty()) {
                $account->sub_accounts = [];
            }
            else {
                $account->sub_accounts = $account->unionUserList->pluck('user_id')->toArray();
            }
        }

        return view('admin.union-assign', [
            'type' => str_replace('_', ' ', $account->account_type),
            'account' => $account,
            'sub_accounts' => $sub_accounts
        ]);
    }

    public function assignStore(Request $request, $id)
    {
        $validated = $request->validate([
            'account_id' => 'required',
            'sub_account_id' => 'required|array|min:1',
        ]);

        abort_if( empty($id) || $validated['account_id'] !== $id, 403);

        $account = UnionAccount::findOrFail($id);

        try {
            if ($account->account_type === 'medical_center') {
                $account->unionMedicalCenterList()->delete();
                $account->unionMedicalCenterList()->createMany(array_map(function ($sub_account_id) {
                    return ['medical_center_id' => $sub_account_id];
                }, $validated['sub_account_id']));
            }
            else if ($account->account_type === 'user') {
                $account->unionUserList()->delete();
                $account->unionUserList()->createMany(array_map(function ($sub_account_id) {
                    return ['user_id' => $sub_account_id];
                }, $validated['sub_account_id']));
            }

            return back()->with('success', 'Sub accounts assigned successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to assign sub accounts');
        }
    }
}
