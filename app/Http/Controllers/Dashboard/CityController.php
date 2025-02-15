<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('translations');

        if ($request->has('search') && !empty($request->search)) {
            $query->whereTranslationLike('name', '%' . $request->search . '%');
        }

        $cities = $query->latest()->paginate(10);
        return view('dashboard.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('dashboard.cities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
        ]);

        $city = new City();
        $city->country_id = 198;
        $city->save();

        foreach ($request->name as $locale => $name) {
            $city->translateOrNew($locale)->name = $name;
        }

        $city->save();

        return redirect()->route('dashboard.cities.index')->with('success', __('site.added_successfully'));
    }

    public function edit(City $city)
    {
        return view('dashboard.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
        ]);

        $city->country_id = 198;

        foreach ($request->name as $locale => $name) {
            $city->translateOrNew($locale)->name = $name;
        }

        $city->save();

        return redirect()->route('dashboard.cities.index')->with('success', __('site.updated_successfully'));
    }

    public function destroy(City $city)
    {
        // Delete related translations first
        $city->translations()->delete();

        // Now delete the city
        $city->delete();
        return redirect()->route('dashboard.cities.index')->with('success', __('site.deleted_successfully'));
    }
}
