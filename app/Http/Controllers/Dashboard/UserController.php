<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Dashboard\hash;
use App\Models\Admin;

class UserController extends Controller
{

    public function __construct()
    {
        // $this->middleware(['permission:read_users'])->only('index');
        // $this->middleware(['permission:create_users'])->only('create');
        // $this->middleware(['permission:update_users'])->only('edit');
        // $this->middleware(['permission:delete_users'])->only('destroy');

    }


    public function index(Request $request)
    {
        /** first method to search*/
        $users =Admin::where(function ($q) use ($request){
        return $q->when($request->input('search'),function ($query) use ($request){
            return $query->where('first_name','like','%'.$request->input('search').'%')
                ->orWhere('last_name','like','%'.$request->input('search').'%');

        });
        })->latest()->paginate(10);

        /** second method to search*/
       /*
         if($request->input('search')){
        $users=User::where('first_name','like','%'.$request->input('search').'%')
       ->orWhere('last_name','like','%'.$request->input('search').'%')->get();

        }else{

            $users =User::whereRoleIs('admin')->get();
        }
       */
        return view("dashboard.users.index",compact('users'));


    }


    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|unique:admins',
            'image'=>'image',
            'password'=>'required|confirmed',
         ]);
        $request_data=$request->except('password,password_confirmation,permissions,image');
        $request_data['password']=bcrypt($request->input('password'));
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Move the file to the public/uploads directory
            $image->move(public_path('/uploads/user_images/'), $filename);
            // Save the path in the database (relative to public)
            $request_data['image'] =  $filename;
        }
//        if($request->input('image')) {
//          Image::make($request->input('image'))
//              ->resize(300, null, function ($constraint) {
//              $constraint->aspectRatio();
//          })->save(public_path('uploads/user_images/'.$request->input('image')->hashName()));
//
//
//            //$request_data['image']=$request->input('image')->store('images','public');
//          }
//        $file = $request->file('image');
//        $file->store('toPath', ['public' => 'uploads']);
//
//        if(!Storage::disk('public_uploads')->put($path, $file_content)) {
//            return false;
//        }

        $user = Admin::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');

    }


    public function edit(Admin $user)
    {
        return view('dashboard.users.edit',compact('user'));
    }

    public function update(Request $request, Admin $user)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>['required',Rule::unique('admins')->ignore($user->id)],
            'image'=>'image',
        ]);
        $request_data=$request->except('permissions','image');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Move the file to the public/uploads directory
            $image->move(public_path('/uploads/user_images/'), $filename);
            // Save the path in the database (relative to public)
            $request_data['image'] =  $filename;
        }

        $user->update($request_data);
        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.users.index');


    }

    public function destroy(Admin $user)
    {
        $user->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');


    }
}
