<?php

namespace App\Http\Controllers\API;

use App\Enum\UserStatusEnum;
use App\Http\Classes\YamamahSMS;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReSendVerifyRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Resources\NotificationsResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\User;
use App\Services\OtpService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOption\Some;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api', ['except' => ['signIn', 'signUp','verify','resendOtpCode']]);
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
            // Save the device information in user_devices table
            if(!empty($request->device_type) && !empty($request->device_token))
            {
                DB::table('user_devices')->insert([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                    'device_type' => $request->device_type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $this->sendOtp($data['phone'] , $code);
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
        if(!empty($request->device_type) && !empty($request->device_token))
        {
            $existingDevice = DB::table('user_devices')
            ->where('user_id', $user->id)
            ->where('device_type', $request->device_type)
            ->where('device_token', $request->device_token)
            ->first();

            if($existingDevice)
            {
                DB::table('user_devices')
                    ->where('id',$existingDevice->id)
                    ->update([
                        'device_token' => $request->device_token,
                        'updated_at' => now(),
                    ]);
            }else{
                DB::table('user_devices')->insert([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                    'device_type' => $request->device_type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }


        }

        $this->sendOtp($data['phone'] , $code);

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
        $class      = new OtpService();
        $response = $class->sendOtp($phone,$code);
        if (isset($response['error'])) {
            return false;
        }
        return true;
    }


    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();
            $data = $request->validated();
            // Check if the request contains a file for profile image
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Store the image and get the path (you can also store it in a specific directory)
                $path = $image->store('profile_images', 'public');  // store in 'public' disk

                // Optionally, you can delete the old image if it exists before storing the new one
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }

                // Save the path of the image in the user's profile
                $data['image'] = $path;
            }
            // Update user details
            $user->update( $data);

            // Return updated user as a resource
            $data = new UserResource($user);

            return $this->success(__('Profile updated successfully'), $data, 200);
        } catch (Exception $e) {
            return $this->errors(__('Something went wrong'), ['error' => $e->getMessage()], 500);
        }
    }

    public function updateUserToken(Request $request)
    {
        try{
            $request->validate([
                'device_token' => 'required|string|max:255',
                'device_type' => 'nullable|string|in:web,android,ios',
            ]);
            // Retrieve the authenticated user
            $user = auth()->user();
            $existingDevice = DB::table('user_devices')
            ->where('user_id', $user->id)
            ->where('device_token', $request->device_token)
            ->first();

            if ($existingDevice) {
                // Update the device token if it has changed
                    DB::table('user_devices')
                        ->where('id', $existingDevice->id)
                        ->update([
                            'device_token' => $request->device_token,
                            'updated_at' => now(),
                        ]);

            } else {
                // Insert a new device entry if not already present
                DB::table('user_devices')->insert([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                    'device_type' => $request->device_type ?? 'android',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $this->success('Updated successfully',200);

        }catch (\Exception $e){

            return $this->errors('Something error',400);

        }


    }
    public function unReadMessagesCount(){

        $notifications  = DB::table('notifications')
            ->where('notifiable_id', Auth::guard('api')->id())->whereNull('read_at')->count();

        return response([
            'status' => 200,
            'notifications_count' => (int)$notifications,
        ]);

    }

    public function readNotification(){

        DB::table('notifications')->where('notifiable_id', Auth::guard('api')->id())->update(['read_at'=>Carbon::now()]);

        return response([
            'status' => 200,
            'message' => 'Read notifications successfully',
        ]);


    }
    public function getNotifications(Request $request){

        $notifications = auth('api')->user()->notifications()->where('type', 'App\Notifications\PushNotification')->paginate($request->paginate??10);
        return $this->success('Updated successfully',new NotificationsResource($notifications),200);
      }

}
