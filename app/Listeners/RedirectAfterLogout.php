<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectAfterLogout
{
    public function handle(Request $request, $event)
    {
        return redirect('/login');
    }
}
