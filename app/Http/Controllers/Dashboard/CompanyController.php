<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
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
            $companies = Company::whereTranslationLike('name','%'.$request->input('search').'%')->latest()->paginate(3);

        }else{
                $companies = Company::latest()->paginate(5);
        }
        return view('dashboard.companies.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.companies.create');
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
            'ar.*'=>'required|unique:company_translations,name',
            'en.*'=>'required|unique:company_translations,name',
            ]);
            Company::create($request->all());
        session()->flash('success',__('site.updated_successfully'));
                return redirect()->route('dashboard.companies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }


    public function edit(Company $company)

    {
        return view('dashboard.companies.edit',compact('company'));
    }


    public function update(Request $request, Company $company)
    {
        $request->validate([

            'ar.*'=>['required',Rule::unique('company_translations','name')->ignore($company->id,'company_id')],
            'en.*'=>['required',Rule::unique('company_translations','name')->ignore($company->id,'company_id')],
            ]);
            $company->update($request->all());
            session()->flash('success',__('site.updated_successfully'));
            return redirect()->route('dashboard.companies.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.companies.index');

    }
}
