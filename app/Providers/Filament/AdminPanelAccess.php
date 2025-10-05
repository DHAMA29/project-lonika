<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Illuminate\Support\Facades\Auth;

class AdminPanelAccess
{
    public static function checkAccess(Panel $panel): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            // Redirect non-admin users
            if ($user->role === 'user') {
                redirect('/peminjaman')->send();
                return false;
            }
            return false;
        }

        return true;
    }
}
