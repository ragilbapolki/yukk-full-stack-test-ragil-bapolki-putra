<?php

namespace App\Http\Controllers;

use App\Models\{User, Saldo, Transaction};

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = array(
            'count_user' => User::latest()->count(),
            'count_trans' => Transaction::latest()->count(),    
            'saldo'      => number_format(Saldo::where('users_id', auth()->user()->id)->first()->saldo),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_dashboard'
        );
        return view('layouts.v_template', $data);
    }
}
