<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MosqueController extends Controller
{
    public function __construct(){

        // $this->middleware(['permission:read_categories'])->only('index');

        // $this->middleware(['permission:create_categories'])->only('create');
        // $this->middleware(['permission:update_categories'])->only('edit');
        // $this->middleware(['permission:delete_categories'])->only('destroy');
    }
    public function index(Request $request)
    {

if($request->input('search')){
    $mosques = Mosque::whereTranslationLike('name','%'.$request->input('search').'%')->latest()->paginate(3);

}else{


        $mosques = Mosque::latest()->paginate(15);
}
        return view('dashboard.mosques.index',compact('mosques'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.mosques.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'ar.*'=>'required|unique:mosque_translations,name',
            'en.*'=>'required|unique:mosque_translations,name',
            ]);
            Mosque::create($request->all());
        session()->flash('success',__('site.updated_successfully'));
                return redirect()->route('dashboard.mosques.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Mosque  $Mosque
     * @return \Illuminate\Http\Response
     */
    public function show(Mosque $Mosque)
    {
        //
    }


    public function edit(Mosque $mosque)

    {
        return view('dashboard.mosques.edit',compact('mosque'));
    }


    public function update(Request $request, Mosque $mosque)
    {
        $request->validate([

            'ar.*'=>['required',Rule::unique('mosque_translations','name')->ignore($mosque->id,'mosque_id')],
            'en.*'=>['required',Rule::unique('mosque_translations','name')->ignore($mosque->id,'mosque_id')],
            ]);
            $mosque->update($request->all());
            session()->flash('success',__('site.updated_successfully'));
            return redirect()->route('dashboard.mosques.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mosque  $mosque
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mosque $mosque)
    {
        $mosque->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.mosques.index');

    }
}
