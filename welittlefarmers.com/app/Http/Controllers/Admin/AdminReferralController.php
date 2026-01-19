<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscountCode;
use App\Models\ReferralSetting;
use App\Models\Referral;

class AdminReferralController extends Controller
{
    public function index()
    {
        $settings = ReferralSetting::all()->pluck('value', 'key');
        // If settings missing, use defaults (though migration handled this)
        return view('admin.referrals.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            ReferralSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Referral settings updated successfully.');
    }

    public function discounts()
    {
        $discounts = DiscountCode::whereNull('user_id')->latest()->get(); // Only admin codes
        return view('admin.referrals.discounts', compact('discounts'));
    }

    public function storeDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discount_codes,code',
            'type' => 'required|in:flat,percent',
            'value_inr' => 'required|numeric',
            'value_usd' => 'required|numeric',
            'min_order_inr' => 'nullable|numeric',
            'min_order_usd' => 'nullable|numeric',
            'max_usage' => 'nullable|integer',
            'expires_at' => 'nullable|date',
        ]);

        DiscountCode::create($request->all());

        return redirect()->back()->with('success', 'Discount code created successfully.');
    }

    public function toggleDiscount($id)
    {
        $discount = DiscountCode::findOrFail($id);
        $discount->is_active = !$discount->is_active;
        $discount->save();

        return redirect()->back()->with('success', 'Discount status updated.');
    }
    
    public function deleteDiscount($id)
    {
         $discount = DiscountCode::findOrFail($id);
         $discount->delete();
         return redirect()->back()->with('success', 'Discount code deleted.');
    }

    public function logs()
    {
        $referrals = Referral::with(['referrer', 'referredUser'])->latest()->paginate(20);
        return view('admin.referrals.logs', compact('referrals'));
    }
}
