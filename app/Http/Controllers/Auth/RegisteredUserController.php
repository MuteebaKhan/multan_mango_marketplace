<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Farm; 
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
    // 1. Validation Rules
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string', 'in:customer,vendor'],
        'phone' => ['nullable', 'string', 'max:20'],
        'address' => ['nullable', 'string'],
        // Conditional validation: Agar user Vendor hai to farm details lazmi hain
        'farm_name' => ['required_if:role,vendor', 'nullable', 'string', 'max:255'],
        'farm_location' => ['required_if:role,vendor', 'nullable', 'string', 'max:255'],
    ]);

    // 2. Database Transaction (Taake agar farm save na ho to user bhi register na ho—Data Safety!)
    $user = DB::transaction(function () use ($request) {
        
        // User create karein
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // customer ya vendor
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Agar register hone wala banda Vendor hai, to uska Farm table entry link karein
        if ($request->role === 'vendor') {
            Farm::create([
                'user_id' => $user->id, // Foreign Key connection
                'farm_name' => $request->farm_name,
                'location' => $request->farm_location,
                'description' => 'Premium royal mango orchards managed by ' . $request->name,
            ]);
        }

        return $user;
    });

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}
