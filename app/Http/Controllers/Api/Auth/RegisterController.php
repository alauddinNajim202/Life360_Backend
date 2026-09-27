<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\SMS;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use ApiResponse, SMS;

    protected array $select;

    public function __construct()
    {
        $this->select = ['id', 'name', 'email', 'phone', 'otp', 'avatar', 'otp_verified_at', 'last_activity_at'];
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 'Validation failed', 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name).'-'.uniqid(),
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'otp' => rand(1000, 9999),
                'otp_expires_at' => now()->addMinutes(5),
                'otp_verified_at' => null,
            ]);

            // Send OTP via SMS
            // if (!empty($user->phone)) {
            //     $message = "প্রিয় {$user->name}, Mess Expert-এ স্বাগতম!\nআপনার ওটিপি কোড: {$user->otp}\n\nমোবাইল অ্যাপ ডাউনলোড করতে লিংকে ক্লিক করুন: https://play.google.com/store/apps/details?id=com.messExpert.app\n\nসাপোর্টের জন্য ফেসবুক গ্রুপ থেকে হেল্প নিতে ক্লিক করুন: https://www.facebook.com/share/19rJwcxX1a";
            //     \App\Helpers\SmsHelper::send($user->phone, $message);
            // }

            // email sent
            if (! empty($user->email)) {
                Mail::to($user->email)->send(new OtpMail($user->otp, $user, 'Verify Your OTP'));
            }

            DB::commit();

            return $this->success($user->only($this->select), 'User registered successfully. Please verify your phone number.', 200);

        } catch (Exception $e) {
            DB::rollBack();

            return Helper::jsonErrorResponse('User registration failed', 500, [$e->getMessage()]);
        }
    }

    public function VerifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users,email',
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 'Validation failed', 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if ($user->otp_verified_at) {
                return $this->error(null, 'Email already verified.', 409);
            }

            if ((string) $user->otp !== (string) $request->otp) {
                return $this->error(null, 'Invalid OTP code', 422);
            }

            if (Carbon::parse($user->otp_expires_at)->isPast()) {
                return $this->error(null, 'OTP has expired. Please request a new OTP.', 422);
            }

            $user->update([
                'otp_verified_at' => now(),
                'otp' => null,
                'otp_expires_at' => null,
            ]);

            $token = auth('api')->login($user);

            return $this->success([
                'token_type' => 'bearer',
                'token' => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'data' => $user->only($this->select),
            ], 'Phone verified successfully', 200);

        } catch (Exception $e) {
            return Helper::jsonErrorResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function ResendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 'Validation failed', 422);
        }

        try {
            $user = User::where('email', $request->email)->first();

            if ($user->otp_verified_at) {
                return $this->error(null, 'Email already verified.', 409);
            }

            $newOtp = rand(1000, 9999);

            $user->update([
                'otp' => $newOtp,
                'otp_expires_at' => now()->addMinutes(5),
            ]);

            // Send the new OTP to the user's email
            if (! empty($user->email)) {
                Mail::to($user->email)->send(new OtpMail($user->otp, $user, 'Verify Your OTP'));
            }

            return $this->success($user->only($this->select), 'A new OTP has been sent to your email.', 200);

        } catch (Exception $e) {
            return $this->error(null, $e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
