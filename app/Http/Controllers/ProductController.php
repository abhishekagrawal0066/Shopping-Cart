<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = Product::with('category', 'images')->get();
            $data = Product::withTrashed()->with('category', 'images')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('images', function ($row) {
                    $imagesHtml = '';
                    foreach ($row->images as $image) {
                        $imagesHtml .= '<img src="' . Storage::url($image->image_path) . '" width="50" height="50" style="margin-right: 5px;" />';
                    }
                    return $imagesHtml;
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('products.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                    $softDeleteBtn = "";
                    if(!$row->trashed()){

                        $softDeleteBtn = '<a href="' . route('products.destroySoft', $row->id) . '" class="delete btn btn-warning btn-sm" data-id="' . $row->id . '" data-url="' . route('products.destroySoft', $row->id) . '">Soft Delete</a>';
                    }
                    $hardDeleteBtn = '<a href="' . route('products.destroyHard', $row->id) . '" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '" data-url="' . route('products.destroyHard', $row->id) . '">Hard Delete</a>';
                    $restoreBtn = $row->deleted_at ? '<a href="' . route('products.restore', $row->id) . '" class="restore btn btn-success btn-sm" data-id="' . $row->id . '" data-url="' . route('products.restore', $row->id) . '">Restore</a>' : '';
                    $uploadImagesBtn = '<button class="btn btn-secondary btn-sm upload-images-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Upload Images</button>';

                    return $editBtn . ' ' . $softDeleteBtn . ' ' . $hardDeleteBtn . ' ' . $restoreBtn .' '. $uploadImagesBtn;
                })
                ->rawColumns(['images', 'action'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::create($request->except('images'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // $destinationPath = public_path('images');
                // $imageName = time() . '_' . $image->getClientOriginalName();
                // $image->move($destinationPath, $imageName);
                // $publicPath = 'images/' . $imageName;
                $image->store('products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $image->store('products'),
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $images = $product->images;
        return view('admin.products.create', compact('product', 'categories', 'images'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product->update($request->except('images'));

        if ($request->has('images')) {
            foreach ($product->images as $image) {
                if(Storage::exists($image->image_path)){
                    Storage::delete($image->image_path);
                }
                $image->delete();
            } 
            foreach ($request->file('images') as $image) {
                // $destinationPath = public_path('images');
                // $imageName = time() . '_' . $image->getClientOriginalName();
                // $image->move($destinationPath, $imageName);
                // $publicPath = 'images/' . $imageName;
                $image->store('products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' =>$image->store('products'),
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('products.index')->with('success', 'Products imported successfully.');
    }

    public function destroyHard(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::delete($image->image_path);
            $image->delete();
        }

        $product->forceDelete();
        return response()->json(['success' => true, 'message' => 'Product Soft Deleted  successfully.']);
        // return redirect()->route('products.index')->with('success', 'Product and associated images deleted successfully.');
    }

    public function destroySoft(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product Deleted successfully.']);
        // return redirect()->route('products.index')->with('success', 'Product soft deleted successfully.');
    }

    public function restore($product)
    {
        $product = Product::withTrashed()->find($product);
        $product->restore();
        return response()->json(['success' => true, 'message' => 'Product restored successfully.']);

    }
    public function uploadImages(Request $request)
    {
        $request->validate([
            'images.*' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);

        $product = Product::withTrashed()->find($request->product_id);
        if ($product) {
            $images = $request->file('images');
            $imagePaths = [];
            if ($images) {
                foreach ($request->file('images') as $image) {
                    $image->store('products');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $image->store('products'),
                    ]);
                }
                return response()->json(['success' => 'Images uploaded successfully']);
            }
        }

        return response()->json(['error' => 'Product not found'], 404);
    }

}
