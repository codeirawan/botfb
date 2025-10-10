<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
  /**
   * Display a list of all categories.
   */
  public function index()
  {
    $categories = Category::orderBy('created_at', 'desc')->paginate(10);
    return view('categories.index', compact('categories'));
  }

  /**
   * Show the form for creating a new category.
   */
  public function create()
  {
    return view('categories.create');
  }

  /**
   * Store a newly created category in the database.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:100|unique:categories,name',
    ]);

    Category::create([
      'name' => $request->name,
    ]);

    return redirect()->route('categories.index')->with('success', 'Category created successfully.');
  }

  /**
   * Show the form for editing a specific category.
   */
  public function edit(Category $category)
  {
    return view('categories.edit', compact('category'));
  }

  /**
   * Update an existing category.
   */
  public function update(Request $request, Category $category)
  {
    $request->validate([
      'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
    ]);

    $category->update([
      'name' => $request->name,
    ]);

    return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
  }

  /**
   * Delete a category.
   */
  public function destroy(Category $category)
  {
    $category->delete();
    return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
  }
}
