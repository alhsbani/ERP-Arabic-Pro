<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanySettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the company settings form.
     */
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;

        return view('settings.company', compact('company'));
    }

    /**
     * Update the company settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'address_ar' => 'required|string',
            'city' => 'required|string|max:100',
            'city_ar' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'tax_number' => 'nullable|string|max:50',
            'commercial_register' => 'nullable|string|max:50',
            'currency' => 'required|string|max:3',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company->update($request->only(
            'name',
            'name_ar',
            'email',
            'phone',
            'address',
            'address_ar',
            'city',
            'city_ar',
            'country',
            'tax_number',
            'commercial_register',
            'currency'
        ));

        return redirect()->route('settings.company')->with('success', __('messages.settings_updated'));
    }
}
