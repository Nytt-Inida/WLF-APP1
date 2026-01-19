<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Course; 
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\SeoMeta;
use SEO;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;


class HomeController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

      public function __construct()
    {
        // Commented out the middleware if you don't want auth to be applied
        // $this->middleware('auth');
    }
   

    public function index()
    {
        $courses = Course::all()->map(function($course) {
            $status = $course->getDynamicStatus();
            $course->cta_text = $status['cta_text'];
            $course->is_paid = $status['is_paid'];
            $course->has_progress = $status['has_progress'];
            // Ensure price_usd is explicitly loaded for the view
            $course->price_usd = $course->price_usd; 
            return $course;
        });

        SEO::setCanonical('https://welittlefarmers.com');

        $seo = SeoMeta::where('page_type', 'home')->first();
        if($seo) {
            SEO::SetTitle($seo->title);
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        } 
        else {
            // Fallback seo
            SEO::setTitle("Little Farmers Academy - Online Farming Courses for Kids");
            SEO::setDescription("Little Farmers Academy offers online farming courses for kids, providing essential skills in agriculture, food science, and sustainable practices.");
            SEO::metatags()->addKeyword(['online education', 'farming courses for kids', 'agriculture education', 'kids farming skills', 'Little Farmers Academy']);
        }

        $stickyBarEnabled = \App\Models\SiteSetting::get('sticky_bar_enabled', 0);
        $stickyBarPriceVal = \App\Models\SiteSetting::get('sticky_bar_price', 0);
        
        // Use centralized logic for Hero Course (ID 1)
        $heroCourse = Course::find(1);
        
        // If sticky bar price is 0, use hero course price as fallback
        $baseINR = $stickyBarPriceVal > 0 ? $stickyBarPriceVal : ($heroCourse ? $heroCourse->price : 0);
        $baseUSD = $heroCourse ? $heroCourse->price_usd : null;

        $stickyBarPrice = convertPrice($baseINR, false, $baseUSD); 
        $stickyBarCoupon = \App\Models\SiteSetting::get('sticky_bar_coupon_code', '');
        $stickyBarPercent = \App\Models\SiteSetting::get('sticky_bar_discount_percent', '');
        $currencySymbol = getCurrencySymbol();

        // Use centralized logic for Hero Course (ID 1)
        $heroCourse = Course::find(1);
        $heroCourseStatus = $heroCourse ? $heroCourse->getDynamicStatus() : ['cta_text' => 'Start Your Free Trial'];
        $heroCourseCta = $heroCourseStatus['cta_text'];

        // Check if user is already enrolled in course ID 1
        $isEnrolled = false;
        if (auth()->check() && $heroCourse) {
            $isEnrolled = $heroCourse->userHasPaid(auth()->user());
        }

        return view('home', compact('courses', 'stickyBarEnabled', 'stickyBarPrice', 'stickyBarCoupon', 'stickyBarPercent', 'currencySymbol', 'heroCourseCta', 'isEnrolled'));
    }

    public function contact()
    {
        SEO::setCanonical('https://welittlefarmers.com/contact');
        $seo = SeoMeta::where('page_type', 'contact')->first();
        if ($seo) {
            SEO::setTitle($seo->title);
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        }
        else {
            // Fallback seo
            SEO::setTitle("Little Farmers Academy - Online Farming Courses for Kids");
            SEO::setDescription("Little Farmers Academy offers online farming courses for kids, providing essential skills in agriculture, food science, and sustainable practices.");
            SEO::metatags()->addKeyword(['online education', 'farming courses for kids', 'agriculture education', 'kids farming skills', 'Little Farmers Academy']);
        }

        return view('contact', compact('seo'));
    }
}