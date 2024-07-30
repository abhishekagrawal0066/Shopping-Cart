<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return DataTables::of($data)
                ->addColumn('action', function($row){
                    $csrfField = csrf_field();
                    $methodField = method_field("DELETE");
                    $editRoute = route('categories.edit', $row->id);
                    $deleteRoute = route('categories.destroy', $row->id);
                    $btn = "<a href='{$editRoute}' class='edit btn btn-primary btn-sm'>Edit</a>";
                    $btn .= "<form action='{$deleteRoute}' method='POST' style='display:inline-block;'>
                                {$csrfField}
                                {$methodField}
                                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                             </form>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.categories.index');
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.create', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['success' => true]);
    }
}