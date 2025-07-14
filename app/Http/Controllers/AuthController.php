<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Function for register admin
     */
    public function adminRegistration(Request $request)
    {
        $validate =  $request->validate([
            'name' => 'required|string',
            'email' => 'email|required',
            'password' => 'required|confirmed',
        ]);
        try {
            User::create($validate);
            return response()->json(['status' => true, 'messaage' => 'Admin Created Successfully'], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }

    /**
     * Function for admin login
     */
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|sting',
        ]);
        try {
            $admin = User::where('email', $request->email)->first();
            if (!$admin) {
                return response()->json(['status' => false, 'messaage' => 'Faild to Find Record'], 404);
            }
            if (!Hash::check($request->password, $admin->password)) {
                return response()->json(['status' => false, 'messaage' => 'Invalid Credentails'], 403);
            }
            $token = $admin->createToken('authtoken')->plainTextToken;
            return response()->json(['status' => true, 'token' => $token, 'messaage' => 'Admin Login Successfully'], 200);
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }

    /**
     * Function for logout
     */
    public function adminLogout(Request $request)
    {
        try {
            $admin = Auth::user();
            $admin->tokens()->delete();
            Auth::logout();
        } catch (\Throwable $th) {
            Log::error('error happend', ['error' => $th->getMessage()]);
            return response()->json(['status' => false, 'messaage' => 'Faild to Fetch Data'], 500);
        }
    }
}
