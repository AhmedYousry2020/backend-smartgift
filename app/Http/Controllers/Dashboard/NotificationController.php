<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;

use App\Notifications\PushNotification;
use App\Http\Controllers\Controller;

use App\Models\User;

use App\Models\PushedNotification;

class NotificationController extends Controller
{
    public function index()
    {
        return view('dashboard.PushNotifications.index', ['notifications' => PushedNotification::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('dashboard.PushNotifications.store');
    }

    public function store(Request $request)
    {
        $request->validate(['title'=>'required|string|max:255', 'content'=>'required|string|max:50000']);

        PushedNotification::create($request->only('title', 'content'));

        $users = User::where('id',1633)->whereHas('devices')->with('devices')->get();
        foreach($users as $user) {
            $user->notify(new PushNotification($request->title, $request->content, $user));

            foreach($user->devices as $device)
            {
                $firebase = new NewFirebaseController();

                switch($device->device_type){
                    case 'android':
                        $firebase->sendAndroidNotification($device->device_token, $firebase->fillAndroidJson($request->title, $request->content, 1, 0));
                    case 'ios':
                        $firebase->sendIOSNotification($device->device_token, $firebase->fillIOSJson($request->title, $request->content), 1, 0);
                }
            }
        }


        return redirect()->route('notifications.index')->with('status', __('general.successfully_completed'));
    }
}
