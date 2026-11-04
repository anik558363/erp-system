<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ইউজার যে রোলের, তাকে সেই ড্যাশবোর্ডে পাঠাবে
        if ($user->hasRole('Owner')) {
            return view('dashboards.owner');
        } elseif ($user->hasRole('Office')) {
            return view('dashboards.office');
        } elseif ($user->hasRole('Salesman')) {
            return view('dashboards.salesman');
        } elseif ($user->hasRole('Warehouse')) {
            return view('dashboards.warehouse');
        } elseif ($user->hasRole('Delivery')) {
            return view('dashboards.delivery');
        }

        // ডিফল্ট ভিউ যদি কোনো রোল না থাকে
        return view('dashboard');
    }
}
