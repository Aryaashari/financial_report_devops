<?php

namespace App\Http\Controllers;

use App\Enums\CategoryType;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::where('user_id', Auth::user()->id)->get();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:categories'],
            'type' => ['required', Rule::enum(CategoryType::class)]
        ]);

        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'user_id' => Auth::user()->id
        ]);

        return response()->json(true);
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => ['required', Rule::unique('categories', 'name')->ignore($category->name, 'name'),],
                'type' => ['required', Rule::enum(CategoryType::class)]
            ]);
    
            $category->update([
                'name' => $request->name,
                'type' => $request->type
            ]);
    
            return response()->json([
                "success" => true,
                "data" => [
                    "name" => $category->name,
                    "type" => $category->type
                ]
            ]);
        } catch(Exception $error) {
            Log::debug($error->getMessage());
            return response()->json([
                "success" => false,
                "data" => null
            ], 500);
        }

    }
    public function destroy(string $name)
    {
        Category::destroy($name);

        return;
    }
}
