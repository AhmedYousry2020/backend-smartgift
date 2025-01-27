<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index()
    {
        $sliders = Slider::all();
        return $this->success(message: __('Data Returned Successfully'), data: SliderResource::collection($sliders), status: 200);
    }

}
