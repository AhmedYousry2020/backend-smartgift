<?php

namespace App\Http\Controllers\API;

use App\Enum\UserStatusEnum;
use App\Http\Classes\YamamahSMS;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReSendVerifyRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use PhpOption\Some;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['signIn', 'signUp','verify','resendOtpCode','refreshToken']]);
    }

    public function signUp(SignUpRequest $request){
        $data           = $request->validated();
        DB::beginTransaction();
        try{
            $code    = randomNumber(6);
            if(app()->environment('local'))
                $code = 123456;

            $data['code']=$code;
            $user = User::create([
                'phone'             => $data['phone'],
                'first_name'        => $data['first_name'],
                'last_name'         => $data['last_name'],
                'status'            => UserStatusEnum::ACTIVE,
                'otp'               => $code,
                'phone_verified_at' => null,
                'create_otp_date'   => now(),
            ]);
            //$this->sendOtp($data['phone'] , $code);
            $data = new UserResource($user);
            DB::commit();
            return $this->success(__('User created successfully'), $data, 200);
        }catch(\Exception $exception){
            DB::rollback();
            return response()->json(["message" => $exception->getMessage() . ' / ' . $exception->getLine(). ' / ' . $exception->getFile() , "code" => 500] , 500);
        }
    }

    public function verify(VerifyRequest $request)
    {
        $data          = $request->validated();
        $user_data     = User::where('phone',$data['phone'])->first();
        $otp_code      = $user_data->otp;
        $phone_session = $user_data->phone;
        $minute        = minuteBetweenTwoDate($user_data->create_otp_date);
        $otp_time_out  = config('sms.timeout');
        if($minute > $otp_time_out)
            return $this->errors(__('Validation errors'), ['code'=>__("The code is Timeout")]);
        elseif ($data['code'] === $otp_code){
            User::where('phone',$phone_session)->update([
                'phone_verified_at' => date('Y-m-d H:i:s'),
            ]);
            $user          = User::where('phone',$phone_session)->first();
               // Generate JWT Token
               $token = JWTAuth::fromUser($user);
               $user['token'] = $token;
            $data          = new UserResource($user);
            return $this->success(__('User Verified successfully'),$data,200);
        }
        return $this->errors(__('Validation errors'), ['code'=>__("The code is incorrect")]);
    }
    public function resendOtpCode(ReSendVerifyRequest $request){
        $data = $request->validated();
        $user = User::where('phone',$data['phone'])->first();
        if($user->phone_verified_at)
            return $this->failure(__('This user is already Verified'));

        $data['otp'] = randomNumber(6);

        if(app()->environment('local'))
            $data['otp'] = 123456;

        $user->update([
            'otp'               => $data['otp'],
            'create_otp_date'   => date('Y-m-d H:i:s'),
            'phone_verified_at' => null,
        ]);
        $user['token'] = '';
        $data          = new UserResource($user);
        $this->sendOtp($data['phone'],$data['otp']);

        return $this->success(__('Code Created successfully'),$data,200);
    }

    public function refreshToken(){
        try{
            // Attempt to refresh the token
            $token = auth('api')->refresh(); // Refresh the token
            $user = auth('api')->user(); // Get the authenticated user
            $user['token'] = $token;
            $data          = new UserResource($user);
            return $this->success('User created successfully',$data,200);
        }catch  (Exception $e) {
            return $this->errors(__('Token is required'), ['token'=>[__("Token is required")]]);
        }
    }

    public function signIn(SignInRequest $request){
        $data = $request->validated();

        // Find user by phone number
        $user = User::where('phone', $data['phone'])
        ->whereIn('status', [UserStatusEnum::ACTIVE, UserStatusEnum::FREEZE])
        ->first();

        if(!$user)
            return $this->errors(__('auth.failed'), ['phone'=>[__('auth.failed')]]);

        $code    = randomNumber(6);
        if(app()->environment('local'))
            $code = 123456;

        $user = User::where('phone', $data['phone'])
            ->whereIn('status',[UserStatusEnum::ACTIVE, UserStatusEnum::FREEZE])
            ->first();

        // Check if not blocked
        if(!$user)
            return $this->failure(__('Your Account is blocked!'));

        $user->update([
            'otp'               => $code,
            'phone_verified_at' => null,
            'create_otp_date'   => date('Y-m-d H:i:s'),
        ]);
        //$this->sendOtp($data['phone'] , $code);

        return $this->success(__('Data Returned Successfully'), new UserResource($user), 200);

    }

    public function signOut(){
        auth()->user()->update(['phone_verified_at' => null]);
        auth()->logout();
        return $this->success(__('User successfully signed out'),[],200);
    }

    public function profile(){
        $token          = request()->bearerToken();
        $user           = auth()->user();
        $user['token']  = $token;
        $data           = new UserResource($user);
        return $this->success('Data Returned Successfully',$data,200);
    }

    protected function sendOtp($phone,$code){
        if(config('sms.enable')){
            $class      = new YamamahSMS();
            $notifiable = [
                'phone'   => $phone,
                'message' => 'Use '.$code.' as code to verify Smart Savings Account',
            ];
            $class->send($notifiable);
        }
        return true;
    }



}
