<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    // Handle Google callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = $this->findOrCreateUser($googleUser, 'google');
            
            Auth::login($user);
            
            return redirect()->intended('/rooms')->with('toast_success', 'Đăng nhập thành công');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('toast_error', 'Đăng nhập Google thất bại');
        }
    }
    
    // Redirect to Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    // Handle Facebook callback
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = $this->findOrCreateUser($facebookUser, 'facebook');
            
            Auth::login($user);
            
            return redirect()->intended('/dashboard');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Đăng nhập Facebook thất bại');
        }
    }
    
    private function findOrCreateUser($socialUser, $provider)
    {
        // Tìm user theo email trước
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // Cập nhật thông tin provider nếu chưa có
            if ($provider === 'google' && !$user->google_id) {
                $user->update([
                    'google_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            } elseif ($provider === 'facebook' && !$user->facebook_id) {
                $user->update([
                    'facebook_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            }
            
            return $user;
        }
        
        // Tạo user mới
        return User::create([
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            $provider . '_id' => $socialUser->getId(),
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('toast_success', 'Đăng xuất thành công!');
    }
}