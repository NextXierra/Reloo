<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('addresses');

        return view('auth::profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string',
            'province_name' => 'required|string|max:100',
            'city_name' => 'required|string|max:100',
            'district_name' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        $userId = Auth::id();
        $isDefault = $request->boolean('is_default');

        // If this is user's first address, make it default automatically
        if (UserAddress::where('user_id', $userId)->count() === 0) {
            $isDefault = true;
        }

        if ($isDefault) {
            UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        }

        UserAddress::create([
            'user_id' => $userId,
            'recipient_name' => $validated['recipient_name'],
            'phone_number' => $validated['phone_number'],
            'address_line' => $validated['address_line'],
            'province_name' => $validated['province_name'],
            'city_name' => $validated['city_name'],
            'district_name' => $validated['district_name'] ?? null,
            'postal_code' => $validated['postal_code'],
            'is_default' => $isDefault,
        ]);

        return back()->with('success', 'Alamat pengiriman baru berhasil ditambahkan.');
    }

    public function deleteAddress(int $id): RedirectResponse
    {
        $address = UserAddress::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $first = UserAddress::where('user_id', Auth::id())->first();
            if ($first) {
                $first->update(['is_default' => true]);
            }
        }

        return back()->with('success', 'Alamat pengiriman berhasil dihapus.');
    }
}
