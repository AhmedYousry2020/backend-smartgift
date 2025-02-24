<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MosqueResource;
use App\Http\Traits\HttpResponsesTrait;
use App\Models\Category;
use App\Models\Mosque;
use Illuminate\Http\Request;

class MosqueController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api');
    }
     /**
     * List mosques with optional filtering by `is_high_need`.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $isHighNeed = $request->query('is_high_need');

        $mosques = Mosque::query()
            ->where('available', true)
            ->when($isHighNeed !== null, function ($query) use ($isHighNeed) {
                $query->where('is_high_need', (bool) $isHighNeed);
            })
            ->with(['translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }])
            ->paginate(60);

        return $this->success(message: __('Data Returned Successfully'), data: MosqueResource::collection($mosques), status: 200);

    }

     /**
     * Get details of a specific mosque.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($id)
    {
        $mosque = Mosque::with(['translations' => function ($query) {
            $query->where('locale', app()->getLocale());
        }])->find($id);

        if (!$mosque) {
            return response()->json(['message' => 'Mosque not found'], 404);
        }

        return $this->success(__('Data Returned Successfully'), new MosqueResource($mosque), 200);
    }


    public function listCategories(Request $request)
    {
        $categories = Category::query()
            ->with(['translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }])
            ->paginate(15);

        return $this->success(message: __('Data Returned Successfully'), data: CategoryResource::collection($categories), status: 200);

    }
}
