<?php

namespace App\Http\Controllers;

use App\Models\Freelancer;
use App\Http\Requests\StoreFreelancerRequest;
use App\Http\Requests\UpdateFreelancerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FreelancerController extends Controller
{
    public function create()
    {
        return view('freelancer.register');
    }

    public function store(StoreFreelancerRequest $request)
    {
        Freelancer::create([
            'name' => $request->name,
            'email' => $request->email,
            'img' => 'img/profile-pictures/default.svg',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('verification.notice');
    }

    public function loginForm()
    {
        return view('freelancer.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $freelancer = Freelancer::where('email', $email)->first();

        if ($freelancer && password_verify($password, $freelancer->password)) {
            session()->put(["LoggedFreelancer" => $freelancer->id]);
            return redirect()->intended(route("freelancers.dashboard"));
        } else {
            return redirect()->back()->with("fail", "Invalid credentials");
        }
    }

    public function dashboard(Request $request)
    {
        if (!session('LoggedFreelancer')) {
            return redirect()->route('freelancers.loginForm');
        }
        $freelancer = Freelancer::find(session('LoggedFreelancer'));
        return view("freelancer.dashboard", ["freelancer" => $freelancer]);
    }

    public function logout()
    {
        if (session()->has('LoggedFreelancer')) {
            session()->pull("LoggedFreelancer");
            return redirect()->route("freelancers.loginForm")->with('success', 'You logged out');
        }
        return "Not logged in";
    }
}