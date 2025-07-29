<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tạo URL redirect cho Google
    public function getGoogleSignInUrl()
    {
        try {
            $url = Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl();
                
            return response()->json([
                'url' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate Google URL: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Tạo URL redirect cho Facebook
    public function getFacebookSignInUrl()
    {
        $url = Socialite::driver('facebook')
            ->stateless()
            ->redirect()
            ->getTargetUrl();
            
        return response()->json([
            'url' => $url
        ]);
    }
    
    // Xử lý callback từ Google
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();
                
            $user = $this->findOrCreateUser($googleUser, 'google');
            
            // Tạo token cho user
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập Google thất bại: ' . $e->getMessage()
            ], 400);
        }
    }
    
    // Xử lý callback từ Facebook
    public function handleFacebookCallback(Request $request)
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                ->stateless()
                ->user();
                
            $user = $this->findOrCreateUser($facebookUser, 'facebook');
            
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập Facebook thất bại: ' . $e->getMessage()
            ], 400);
        }
    }

    // Thêm method này để xử lý OAuth redirect từ popup
    public function handleOAuthCallback(Request $request, $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $user = $this->findOrCreateUser($socialUser, $provider);
            $token = $user->createToken('auth-token')->plainTextToken;
            
            // Trả về HTML để đóng popup và truyền data về parent
            return view('oauth-callback', [
                'success' => true,
                'token' => $token,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            return view('oauth-callback', [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    // Lấy thông tin user hiện tại
    public function getUser(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
    
    // Đăng xuất
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
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
            'email_verified_at' => now(),
        ]);
    }
}