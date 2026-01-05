<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\PublicProposalAcceptController;
use App\Http\Controllers\PublicProposalRejectFormController;
use App\Http\Controllers\PublicProposalRejectController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});


# Public Appointment Proposal Routes
Route::prefix('p')->middleware('throttle:30,1')->group(function () {
    Route::get('{token}/accept', PublicProposalAcceptController::class)
        ->name('public.proposals.accept')
        ->middleware('signed');

    Route::get('{token}/reject', PublicProposalRejectFormController::class)
        ->name('public.proposals.reject.form')
        ->middleware('signed');

    Route::post('{token}/reject', PublicProposalRejectController::class)
        ->name('public.proposals.reject')
        ->middleware('signed');
});

