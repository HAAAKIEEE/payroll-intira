<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';
    public string $username = ''; // ganti email → username

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->username = Auth::user()->username; // ambil username
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendVerificationNotification(): void
    {
        // Jika login tidak lagi berbasis email,
        // maka verifikasi email bisa dihapus
        // atau fungsi ini bisa dihilangkan

        Session::flash('status', 'Username updated successfully.');
    }
}
