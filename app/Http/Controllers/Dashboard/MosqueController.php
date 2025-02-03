<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MosqueController extends Controller
{
    public function __construct(){
    }
    public function index(Request $request)
    {
            $mosques = Mosque::latest()->paginate(15);
            $mosques = Mosque::when($request->input('search'),function($q) use($request){
                return $q->whereTranslationLike('name','%'.$request->input('search').'%');

                })->when($request->input('category_id'),function($q) use($request){
                  return $q->where('category_id','like','%'.$request->input('category_id').'%');
                })->latest()->paginate(15);

        return view('dashboard.mosques.index',compact('mosques'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $cities = City::all();
        return view('dashboard.mosques.create',compact('categories','cities'));
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
            'lat'=>'required',
            'lng'=>'required',
            'city_id'=>'required',
            'category_id'=>'required',
            'address'=>'required',
            'image'=>'file'
            ]);

        $request_data=$request->except('image');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Move the file to the public/uploads directory
            $image->move(public_path('uploads/mosque_images/'), $filename);
            // Save the path in the database (relative to public)
            $request_data['image'] = 'uploads/mosque_images/' . $filename;
        }

        Mosque::create($request_data);

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
        $categories = Category::all();
        $cities = City::all();
        return view('dashboard.mosques.edit',compact('mosque','categories','cities'));
    }


    public function update(Request $request, Mosque $mosque)
    {
        $request->validate([

            'ar.*'=>['required',Rule::unique('mosque_translations','name')->ignore($mosque->id,'mosque_id')],
            'en.*'=>['required',Rule::unique('mosque_translations','name')->ignore($mosque->id,'mosque_id')],
            'lat'=>'required',
            'lng'=>'required',
            'city_id'=>'required',
            'category_id'=>'required',
            'address'=>'required',
            'image'=>'file'
            ]);

        $request_data=$request->except('image');
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = time() . '.' . $image->getClientOriginalExtension();
            // Move the file to the public/uploads directory
            $image->move(public_path('uploads/mosque_images/'), $filename);
            // Save the path in the database (relative to public)
            $request_data['image'] = 'uploads/mosque_images/' . $filename;
        }

        $mosque->update($request_data);
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
