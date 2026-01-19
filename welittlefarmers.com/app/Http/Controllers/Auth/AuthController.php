<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\SeoMeta;
use SEO;


class AuthController extends Controller
{

    public function updateProfile(Request $request)
{
    // Create a validator instance and define the validation rules
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $request->user_id,
        'school_name' => 'nullable|string|max:255',
        'age' => 'required|integer|min:1',
        'country' => 'nullable|string|max:255',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        } else {
            return back()->withErrors($validator)->withInput();
        }
    }

    // Fetch the user from the database
    $user = User::findOrFail($request->user_id);

    // Update the user's profile information
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'school_name' => $request->school_name,
        'age' => $request->age,
        'country' => $request->country,
    ]);

    // Prepare the response data
    $response = [
        'code' => 200,
        'message' => 'Profile updated successfully!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'school_name' => $user->school_name,
            'age' => $user->age,
            'country' => $user->country,
        ],
    ];

    // Check if the request expects a JSON response
    if ($request->expectsJson()) {
        // Return the user profile information as JSON
        return response()->json($response, 200);
    } else {
        // Redirect back to the profile screen with a success message
            return redirect()->route('profile')->with('status', 'Profile updated successfully!');
    }
}

     public function showUpdateProfileForm()
    {
        // Retrieve SEO meta data
        $seo = SeoMeta::where('page_type', 'update_profile')->first();

        if ($seo) {
            SEO::setTitle($seo->title);
            SEO::setDescription($seo->description);
            if ($seo->keywords) {
                SEO::metatags()->addKeyword(explode(',', $seo->keywords));
            }
        }
        else {
            // Fallback SEO settings
            SEO::setTitle('Update Profile - Little Farmers Academy');
            SEO::setDescription('Update your profile information at Little Farmers Academy.');
            SEO::metatags()->addKeyword(['profile update', 'Little Farmers Academy']);
        }
        
        return view('auth.update-profile', compact('seo'));
    }

}