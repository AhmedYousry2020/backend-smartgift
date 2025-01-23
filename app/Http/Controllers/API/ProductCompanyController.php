<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Product;

class ProductCompanyController extends Controller
{
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

        return response()->json($companies);
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
            ->with(['translations' => function ($query) {
                $query->where('locale', app()->getLocale());
            }])
            ->get();

        return response()->json($products);
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
        }])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}
