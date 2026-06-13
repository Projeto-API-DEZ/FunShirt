<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Requests\ProfileUpdateFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('customer');

        if ($user->isStaff()) {
            abort(403, 'Staff accounts cannot edit profile details.');
        }

        return view('profile', compact('user'));
    }

    public function update(ProfileUpdateFormRequest $request)
    {
        $user = Auth::user();

        if ($user->isStaff()) {
            abort(403, 'Staff accounts cannot edit profile details.');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated, $request) {
            if ($request->hasFile('photo_file')) {
                if ($user->hasUploadedPhoto()) {
                    Storage::disk('public')->delete($user->normalizedPhotoPath());
                }

                $file = $request->file('photo_file');
                $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('photos', $file, $filename);
                $user->photo_url = $filename;
            }

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
            ]);

            if ($user->isCustomer()) {
                // Dados especificos de cliente so sao atualizados para contas cliente.
                $user->customer()->update([
                    'nif' => $validated['nif'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'default_payment_type' => $validated['default_payment_type'] ?? null,
                    'default_payment_ref' => $validated['default_payment_ref'] ?? null,
                ]);
            }
        });

        return redirect()->route('profile.edit')->with('alert-success', 'Profile records updated successfully.');
    }
}
