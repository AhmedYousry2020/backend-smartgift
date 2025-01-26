<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ProductResource;
use App\Http\Traits\HttpResponsesTrait;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;

class ProductCompanyController extends Controller
{
    use HttpResponsesTrait;

    public function __construct(){
        $this->middleware('auth:api');
    }
     /**
     * List all companies with their translations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listCompanies()
    {
        $companies = Company::with(['translations' => function ($query) {
            $query->where('locale', app()->getLocale());
        }])->get();

        return $this->success(
            message: __('Data Returned Successfully'),
            data: CompanyResource::collection($companies),
            status: 200
        );
    }

    /**
     * List all products with optional company filter.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProducts(Request $request)
    {
        $companyId = $request->query('company_id');

        $products = Product::query()
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->with([
                'translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            },
            'company.translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }
        ],

            )
            ->get();

            return $this->success(
                message: __('Data Returned Successfully'),
                data: ProductResource::collection($products),
                status: 200
            );

    }

    /**
     * Get details of a specific product.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function productDetails($id)
    {
        $product = Product::with(['translations' => function ($query) {
            $query->where('locale', app()->getLocale());
        },
        'company.translations' => function ($query) {
            $query->where('locale', app()->getLocale());
        }
        ])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return $this->success(
            message: __('Data Returned Successfully'),
            data: new ProductResource($product),
            status: 200
        );
    }
}
