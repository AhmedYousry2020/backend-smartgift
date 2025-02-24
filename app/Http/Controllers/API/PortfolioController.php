<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PortfolioResource;
use App\Http\Resources\SettingResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\Portfolio;
use App\Models\Setting;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{

    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api',['except' => ['getSettings']]);
    }
    public function index()
    {
        $portfolios = Portfolio::get();

        return $this->success(message: __('Data Returned Successfully'), data: PortfolioResource::collection($portfolios), status: 200);

    }
    public function getSettings()
    {
        $settings = Setting::with(['translations' => function ($query) {
            $query->where('locale', app()->getLocale());
        }])->get();
        return $this->success(message: __('Data Returned Successfully'), data: SettingResource::collection($settings), status: 200);

    }

    public function show($id)
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) {
            return response()->json(['message' => 'Portfolio not found'], 404);
        }
        return $this->success(message: __('Data Returned Successfully'), data: new PortfolioResource($portfolio), status: 200);

    }
}
