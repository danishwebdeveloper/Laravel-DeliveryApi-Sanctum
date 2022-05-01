<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::all();
        return $this->successResponse($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'ItemName' => 'required|alpha|max:20',
            'ItemPrice' => 'required|numeric|min:0.10',
            'ItemProperties' => 'required|json',
        ];
        $this->validate($request, $rules);
        $product = Product::create($request->all());
        return $this->successResponse($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $this->successResponse($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $rules = [
            'ItemName' => 'alpha|max:20',
            'ItemPrice' => 'numeric|min:0.10',
            'ItemProperties' => 'json',
        ];
        $this->validate($request, $rules);

        $product = Product::findOrFail($id);
        $product->fill($request->all());
        if ($product->isClean()) {
            return $this->errorResponse("At least one Field should be changed, Thanks!", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product->update($request->all());
        return $this->successResponse($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $this->successResponse($product);

    }
}