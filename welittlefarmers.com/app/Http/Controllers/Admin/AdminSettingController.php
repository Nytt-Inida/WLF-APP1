<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Course;

class AdminSettingController extends Controller
{
    public function index()
    {
        $stickyBarEnabled = SiteSetting::get('sticky_bar_enabled', 0);
        $stickyBarPrice = SiteSetting::get('sticky_bar_price', 1);
        $stickyBarCoupon = SiteSetting::get('sticky_bar_coupon_code', '');
        $stickyBarPercent = SiteSetting::get('sticky_bar_discount_percent', '');


        // Main Course (ID 1)
        $mainCourse = Course::find(1);

        return view('admin.settings.index', compact(
            'stickyBarEnabled', 
            'stickyBarPrice', 
            'stickyBarCoupon', 
            'stickyBarPercent', 

            'mainCourse'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sticky_bar_discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        SiteSetting::set('sticky_bar_enabled', $request->has('sticky_bar_enabled') ? 1 : 0);
        SiteSetting::set('sticky_bar_coupon_code', $request->input('sticky_bar_coupon_code'));
        SiteSetting::set('sticky_bar_discount_percent', $request->input('sticky_bar_discount_percent'));


        // Handle Main Course Price
        if ($request->has('course_price')) {
            $mainCourse = Course::find(1);
            if ($mainCourse) {
                $mainCourse->price = $request->input('course_price');
                $mainCourse->price_usd = $request->input('course_price_usd', 0);
                $mainCourse->save();
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
