<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\StoreFreelancerRequest;
use App\Http\Requests\UpdateFreelancerRequest;
use App\Models\BankAccounts;
use App\Models\Category;
use App\Models\Order;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class VendorController extends Controller
{

    public function dashboard(Request $request){
        if (!session('LoggedVendor')) {
            return redirect()->route('vendors.loginForm');
        }
        $vendor = Vendor::find(session('LoggedVendor'));
        $bankAccount = BankAccounts::where('user_type', 'vendor')->where('user_id', $vendor->id)->first();
        $currentAmount = $bankAccount ? $bankAccount->current_balance : 0;

        $orderCount = $vendor->orders()->count(); 
        $cancelledOrderCount = $vendor->orders()->where("orders.status", "cancelled")->count();
        $pendingOrderCount = $vendor->orders()->where("orders.status", "pending")->count();

        // Fetch latest notifications (unread first)
        $notifications = Notification::where('user_id', $vendor->id)
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view("vendor.dashboard", [
            "vendor" => $vendor,
            "orderCount" => $orderCount,
            "currentAmount" => $currentAmount,
            "cancelledOrderCount" => $cancelledOrderCount,
            "pendingOrderCount" => $pendingOrderCount,
            "notifications" => $notifications,
        ]);
    }

    public function create(){
        return view('vendor.register');
    }
     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:vendors',
        'password' => 'required|string|min:8',
    ]);

    Vendor::create([
        'name' => $request->name,
        'email' => $request->email,
        'img' => 'img/profile-pictures/default.svg',
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('vendors.loginForm')
        ->with('success', 'Account successfully registered! Please login.');
}

    public function loginForm(){
        return view('vendor.login');
    }
    
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        
        // Retrieve the user data from the database
        $vendor = Vendor::where('email', $email)->first();
    
        if ($vendor) {
            // Verify the password
            if (password_verify($password, $vendor->password)) {
                // Password is correct, create a session and return the user ID
                session()->put(["LoggedVendor" => $vendor->id]);
                return redirect()->intended(route("vendors.dashboard"));
            } else {
                // Password is incorrect
                return redirect()->back()->with("fail", "Password is not correct");
            }
        } else {
            // User not found
            return redirect()->back()->with("fail", "Email is not there");
        }
    }

    public function logout(){
        if (session()->has('LoggedVendor')) {
            session()->pull("LoggedVendor");
            return redirect()->route("vendors.loginForm")->with('success', 'you logged out');
        }else{
            return "m";
        }
    }

    function financesDashboard(Request $request) {
        if (!session('LoggedVendor')) {
            return redirect()->route('vendors.loginForm');
        }
        $vendor = Vendor::find(session('LoggedVendor'));

        // Retrieve bank account current balance (assuming there's only one bank account for the freelancer)
        $bankAccount = BankAccounts::where('user_type', 'vendor')->where('user_id', $vendor->id)->first();
        $currentBalance = $bankAccount ? $bankAccount->current_balance : 0;

        // Retrieve recent 10 transactions
        $lastTransactions = Transactions
            ::where("to", $vendor->id)->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view("vendor.finances.dashboard", [
            "vendor" => $vendor,
            "currentBalance" => $currentBalance,
            "lastTransactions" => $lastTransactions,
        ]);
    }

    function withdrawPage(Request $request) {
        if (!session('LoggedVendor')) {
            return redirect()->route('vendors.loginForm');
        }
        $vendor = Vendor::find(session('LoggedVendor'));
        $bankAccount = BankAccounts::where('user_type', 'vendor')->where('user_id', $vendor->id)->first();
        $currentAmount = $bankAccount ? $bankAccount->current_balance : 0;

        return view("vendor.finances.withdraw", [
            "vendor" => $vendor,
            "currentAmount" => $currentAmount,
            "bankAccount" => $bankAccount
        ]);
    }
    
    public function withdrawCash(Request $request){
        if (!session('LoggedVendor')) {
            return redirect()->route('vendors.loginForm');
        }
        $vendor = Vendor::find(session('LoggedVendor')); // Assuming the freelancer is authenticated
        $amount = $request->input('amount');

        // Validate the amount (add more validation as needed)
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Retrieve the bank account associated with the admin
        $bankAccount = BankAccounts::where('user_type', 'vendor')->where('user_id', $vendor->id)->first();

        if (!$bankAccount) {
            return response()->json(['error' => 'Bank account not found'], 404);
        }

        // Check if the withdrawal amount is greater than the current balance
        if ($amount > $bankAccount->current_balance) {
            return response()->json(['error' => 'Insufficient funds'], 422);
        }

        // Deduction
        $deduction =  ($amount * 4) / 100;
        $amount = $amount - $deduction;
        $AdminBankAccount = BankAccounts::where('user_type', 'admin')->first();
        $AdminBankAccount->current_balance += $deduction;
        $AdminBankAccount->save();

        // Update the current_balance
        $bankAccount->current_balance -= $amount;
        $bankAccount->save();

        // You can also create a new transaction record in the transactions table if needed

        // Return the updated bank account information
        return response()->json(['current_balance' => $bankAccount->current_balance]);
    }

    function profilePage(Request $request) {
        if (!session('LoggedVendor')) {
            return redirect()->route('vendors.loginForm');
        }
        $vendor = Vendor::find(session('LoggedVendor'));
        $bankAccount = BankAccounts::where("user_type", "vendor")->where("user_id", session('LoggedVendor'))->first();
        return view("vendor.profile", [
            "vendor" => $vendor,
            "bankAccount" => $bankAccount
        ]);
    }

    public function update(Request $request, $id){
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB limit
            'password' => 'nullable',
        ]);

        // Delete old profile image if it exists and not named "default.svg"
        if ($vendor->img && $vendor->img !== 'img/profile-pictures/default.svg' && file_exists(public_path($vendor->img))) {
            unlink(public_path($vendor->img));
        }

        // Update name and email
        $vendor->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update profile image
        if ($request->hasFile('img')) {
            $imageName = time() . '_' . $request->file('img')->getClientOriginalName();
            $request->file('img')->move(public_path('img/profile-pictures'), $imageName);

            $vendor->update([
                'img' => 'img/profile-pictures/' . $imageName,
            ]);
        }

        // Update password if provided
        if ($request->filled('password')) {
            $vendor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('vendors.profile')->with('success', 'Profile updated successfully.');
    }

    function show(Request $request, $id) {
        $categories = Category::all();
        $vendor = Vendor::findOrFail($id);

        return view("public.vendor", [
            "categories" => $categories,
            "vendor" => $vendor,
        ]);
    }

}
