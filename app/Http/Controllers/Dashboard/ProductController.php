<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
   public function __construct(){


//     $this->middleware(['permission:read_products'])->only('index');

// $this->middleware(['permission:create_products'])->only('create');
// $this->middleware(['permission:update_products'])->only('edit');
// $this->middleware(['permission:delete_products'])->only('destroy');

   }
    public function index(Request $request)
    {
        $companies =Company::all();
        $products = Product::when($request->input('search'),function($q) use($request){
        return $q->whereTranslationLike('name','%'.$request->input('search').'%');

        })->when($request->input('company_id'),function($q) use($request){
return $q->where('company_id','like','%'.$request->input('company_id').'%');
        })->latest()->paginate(3);

        return view ('dashboard.products.index',compact('products','companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view ('dashboard.products.create',compact('companies'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'company_id'=>'required',
        'ar.*'=>'required|unique:product_translations,name,description',
        'en.*'=>'required|unique:product_translations,name,description',
        'price'=>'required',
        'bottle_count'=>'required'

        ]);
        $request_date = $request->except('image');

        if($request->hasFile('image')){
            // Get filename with the extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore =$filename.'_'.time().'.'.$extension;
            // Upload Image ده الي بينقل الصوره للمكان الي عايزه
            $path = $request->file('image')->storeAs('public/product_images',$fileNameToStore);

            $request_date['image'] = $fileNameToStore;
        }
        Product::create($request_date);
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.products.index');

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
    public function edit(Product $product)
    {
        $companies = Company::all();

        return view('dashboard.products.edit',compact('product','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product)
    {
        $request->validate([
            'company_id'=>'required',
            'ar.*'=>['required',Rule::unique('product_translations','name','description')->ignore($product->id,'product_id')],
            'en.*'=>['required',Rule::unique('product_translations','name','description')->ignore($product->id,'product_id')],
            'price'=>'required',
            'bottle_count'=>'required'

            ]);
            $request_date = $request->except('image');
            if($request->hasFile('image')){

            if($request->input('image')!= 'default.png' ){
                Storage::disk('public')->delete('/uploads/product_images/'.$product->image);

            }
            // Get filename with the extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore =$filename.'_'.time().'.'.$extension;
            // Upload Image ده الي بينقل الصوره للمكان الي عايزه
            $path = $request->file('image')->storeAs('public/product_images',$fileNameToStore);

            $request_date['image'] = $fileNameToStore;
        }
        $product->update($request_date);
        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');

    }


    public function destroy(Product $product)
    {
        if($product->image!= 'default.png' ){
            Storage::disk('public')->delete('/uploads/product_images/'.$product->image);

        }
        $product->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');

    }
}
