<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Display product list
    public function index(Request $request)
    {
        $products = session('products', []);
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search);
            $products = array_filter($products, function($product) use ($search) {
                return str_contains(strtolower($product['name']), $search);
            });
        }
        
        // Sort functionality
        if ($request->has('sort')) {
            $sortField = $request->sort;
            $sortOrder = $request->get('order', 'asc');
            
            usort($products, function($a, $b) use ($sortField, $sortOrder) {
                if ($sortField == 'name') {
                    $result = strcmp($a['name'], $b['name']);
                } else if ($sortField == 'price') {
                    $result = $a['price'] <=> $b['price'];
                } else {
                    return 0;
                }
                return $sortOrder == 'desc' ? -$result : $result;
            });
        }
        
        return view('products.index', compact('products'));
    }
    
    // Show create form
    public function create()
    {
        return view('products.create');
    }
    
    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);
        
        $products = session('products', []);
        
        // Generate unique ID
        $id = empty($products) ? 1 : max(array_column($products, 'id')) + 1;
        
        // Handle image upload
        $imagePath = 'placeholder.png';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $imagePath = $imageName;
        }
        
        // Add product to session
        $products[] = [
            'id' => $id,
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath
        ];
        
        session(['products' => $products]);
        
        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }
    
    // Show edit form
    public function edit($id)
    {
        $products = session('products', []);
        $product = collect($products)->firstWhere('id', $id);
        
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found!');
        }
        
        return view('products.edit', compact('product'));
    }
    
    // Update product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);
        
        $products = session('products', []);
        $index = collect($products)->search(function($product) use ($id) {
            return $product['id'] == $id;
        });
        
        if ($index === false) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found!');
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if not placeholder
            if ($products[$index]['image'] != 'placeholder.png' && file_exists(public_path('images/' . $products[$index]['image']))) {
                unlink(public_path('images/' . $products[$index]['image']));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $products[$index]['image'] = $imageName;
        }
        
        $products[$index]['name'] = $request->name;
        $products[$index]['price'] = $request->price;
        
        session(['products' => $products]);
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }
    
    // Delete product
    public function destroy($id)
    {
        $products = session('products', []);
        $index = collect($products)->search(function($product) use ($id) {
            return $product['id'] == $id;
        });
        
        if ($index === false) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found!');
        }
        
        // Delete image if not placeholder
        if ($products[$index]['image'] != 'placeholder.png' && file_exists(public_path('images/' . $products[$index]['image']))) {
            unlink(public_path('images/' . $products[$index]['image']));
        }
        
        array_splice($products, $index, 1);
        session(['products' => $products]);
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
