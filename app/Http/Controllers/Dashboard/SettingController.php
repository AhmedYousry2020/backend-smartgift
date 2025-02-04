<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class SettingController extends Controller
{

    public function index(Request $request)
    {
       
        $settings = Setting::all();

        return view ('dashboard.settings.index',compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('dashboard.settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
       
        'ar.*'=>'required|unique:setting_translations,terms_and_conditions,privacy_policy',
        'en.*'=>'required|unique:setting_translations,terms_and_conditions,privacy_policy',

        ]);

        Setting::create($request->all());
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.settings.index');

    }

   public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {

        return view('dashboard.settings.edit',compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Setting $setting)
    {
        $request->validate([
       
            'ar.*'=>'required|unique:setting_translations,terms_and_conditions,privacy_policy',
            'en.*'=>'required|unique:setting_translations,terms_and_conditions,privacy_policy',
    
            ]);
            
        $setting->update($request->all());
        session()->flash('success',__(key: 'site.updated_successfully'));
        return redirect()->route('dashboard.settings.index');

    }


    public function destroy(Setting $setting)
    {
        
        $setting->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.settings.index');

    }
}
