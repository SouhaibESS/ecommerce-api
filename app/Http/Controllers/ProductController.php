<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Image;
use App\Order;
use App\Product;
use Validator;

class ProductController extends Controller
{
    public function index() 
    {
        return ProductResource::collection(Product::paginate(15));
    }

    public function show($id)
    {
        $product = Product::find($id);
        if(is_null($product))
        {
            return response()->json('Product not found',404);
        }
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rating' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $product = $request->isMethod('put') ? Product::findOrFail($request->id) : new Product;

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->rating = $request->input('rating');
        $product->description = $request->input('description');
        if($request->input('quantity'))
            $product->quantity = $request->input('quantity');
        $product->save();
        foreach($request['images'] as $key => $image)
        {
            $imageName = 'product_' . $product->id . '_image_' . $key . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $isMain = 0;
            if($key ==  0)
                $isMain = 1;

            // creating the product images
            $image =  new Image;
            $image->product_id = $product->id;
            $image->image_path = 'http://127.0.0.1:8000/images/' . $imageName;
            $image->is_main = $isMain;
            $image->save();
        }


        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $images = $product->images;
        $image_pointer = [];
        foreach($images as $key => $image)
        {
            $base = 'http://127.0.0.1:8000';
            $image_pointer[$key] = str_replace($base, './../public', $image->image_path);
            $image_pointer[$key] = realpath($image_pointer[$key]);
            unlink($image_pointer[$key]);
        }

        if($product->delete())
        {
            return new ProductResource($product);
        }
    }

    public function order(Request $request, $id)
    {
        // get the product by it's ID from the DB
        $product = Product::find($id);
        if(is_null($product))
        {
            return response()->json('Product not found',404);
        }

        $rules = [
            'client_name' => 'required',
            'client_email' => 'required',
            'client_phone_number' => 'required',
            'ordered_quantity' => 'required',
            'total_price' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return response()->json([
                'success' => true,
                'errors' => $validator->errors()
            ], 400);
        }

        // check if the quatity ordered is hogher than the one in the stock
        if($product->quantity < $request->input('ordered_quantity'))
        {
            return response()->json([
                'success' => false,
                'message' => 'quantity ordered is bigger than the product quatity'
            ]);
        }

        // creating new order
        $order = $product->orders()->create([
            'client_name' => $request->input('client_name'),
            'client_email' => $request->input('client_email'),
            'client_phone_number' => $request->input('client_phone_number'),
            'ordered_quantity' => $request->input('ordered_quantity'),
            'price' => $request->input('total_  price')
        ]);

        // updating the product quantity after making the order
        $product->quantity -= $request->input('ordered_quantity');
        $product->save();

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
}
