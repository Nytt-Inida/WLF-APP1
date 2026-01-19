<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Course;
use Illuminate\Support\Facades\View;
use App\Models\SeoMeta;
use SEO;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VideoSecurityMiddleware;

use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS if the application is accessed via ngrok or has https in APP_URL
        if (str_contains(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $allCourses = Course::all()->map(function($course) {
                $status = $course->getDynamicStatus();
                $course->cta_text = $status['cta_text'];
                $course->is_paid = $status['is_paid'];
                $course->has_progress = $status['has_progress'];
                $course->price_usd = $course->price_usd; // Explictly load
                return $course;
            });
            $view->with('allCourses', $allCourses);
        });

        View::composer(['blog', 'blog1', 'blog2', 'blog3', 'blog4', 'blog5', 'blog6'], function ($view) {
            // Fetch global default SEO tags from DB if available
            $seo = SeoMeta::where('page_type', 'home')->first();

            if ($seo) {
                SEO::setTitle($seo->title);
                SEO::setDescription($seo->description);
                if ($seo->keywords) {
                    SEO::metatags()->addKeyword(explode(',', $seo->keywords));
                }
            } else {
                // Fallback SEO settings
                SEO::setTitle('We Little Farmer - Online Farming Courses for Kids');
                SEO::setDescription('We Little Farmer offers online farming courses for kids, providing essential skills in agriculture, food science, and sustainable practices.');
                SEO::metatags()->addKeyword([
                    'online education',
                    'farming courses for kids',
                    'agriculture education',
                    'kids farming skills',
                    'We Little Farmer'
                ]);
            }
        });

        // Middleware registration for video security
        Route::aliasMiddleware('video.security', VideoSecurityMiddleware::class);
    }
}
