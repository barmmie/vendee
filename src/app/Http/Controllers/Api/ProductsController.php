<?php
namespace App\Http\Controllers\Api;

use App\Http\Requests\DeleteProductRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductsController {

    public function index(Request $request)
    {

        $products = Product::query()
            ->when($request->get('search'), function (Builder $query) use ($request) {
                $search = $request->get('search');
                return $query->where('productName', 'LIKE', '%'.$search.'%');
            })
            ->when($request->user()->is_seller, function (Builder $query) use ($request) {
                return $query->where('sellerId', $request->user()->id);
            })
            ->paginate($request->get('perPage'));

        return ProductResource::collection(
            $products
        );
    }

    public function store(CreateProductRequest $request)
    {
        return new ProductResource(
            $request->user()->products()->create(
                $request->safe()
                    ->merge([
                        'sellerId' => auth()->user()->id
                    ])
                    ->only('productName', 'sellerId', 'cost', 'amountAvailable')
                )
        );
    }

    public function show(int $id)
    {
        return new ProductResource(
            Product::findOrFail($id)
        );
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::findOrFail($id);

        $product->update(
            $request->safe()
                ->only('productName', 'cost', 'amountAvailable')
        );

        return new ProductResource($product);

    }

    public function destroy(DeleteProductRequest $deleteUserRequest, int $id)
    {

        $product = Product::findOrFail($id);

        $product->delete();

        return response()->noContent();
    }
}
