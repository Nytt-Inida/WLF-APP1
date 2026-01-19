<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Applying auth middleware to all actions within this controller
        $this->middleware('auth');
    }

    public function index()
    {
        // Assuming you might want to pass some data to the dashboard view
        $data = [
            'message' => 'Welcome to your dashboard',
            // Additional data can be passed here
        ];

        return view('dashboard', compact('data'));
    }
}