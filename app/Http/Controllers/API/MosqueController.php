<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MosqueResource;
use App\Http\Traits\HttpResponsesTrait;
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
            ->when($isHighNeed !== null, function ($query) use ($isHighNeed) {
                $query->where('is_high_need', (bool) $isHighNeed);
            })
            ->with(['translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }])
            ->paginate(15);

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
}
