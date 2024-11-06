<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $all = Category::orderBy('id', 'desc')->paginate(10);
        return view('admin.category', ['categories' => $all]);
    }

    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->is_active = $request->is_active ?? 0;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->name = $request->name;
        $category->is_active = $request->is_active ?? 0;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }


    public function delete(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
