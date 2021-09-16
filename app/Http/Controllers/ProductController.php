<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Symfony\Component\Mime\Message;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::all();
        return response()->json([
            "success" => true,
            "message" => 'data berhasil dimuat',
            "data" => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'nama' => 'required|string',
            'harga' => 'required|integer',
            'warna' => 'required|string',
            'kondisi' => 'required|in:baru,lama',
            'deskripsi' => 'string'
        ]);
        if ($validator) {
            $data = $request->all();
            $product = Product::create($data);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambah',
                'data' => $product
            ], 201);
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'data berhasil dimuat',
            'data' => $product
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json(["message" => "Data not found", 404]);
        }
        $this->validate($request, [
            'nama' => 'string',
            'harga' => 'integer',
            'warna' => 'string',
            'kondisi' => 'in:baru,lama',
            'deskripsi' => 'string'
        ]);
        $data = $request->all();
        $product->update($data);

        return response()->json([$product, 200]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil terhapus'
        ], 200);
    }
}
