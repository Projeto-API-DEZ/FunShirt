<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TshirtImage;
use App\Requests\TshirtImageFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TshirtImageController extends Controller
{
    public function index()
    {
        // $user = Auth::user();

        // if ($user->isCustomer()) {
        //     $images = TshirtImage::where('customer_id', $user->id)->latest()->paginate(10);

        //     return view('tshirt_images.my_designs', compact('images'));
        // }

        // $images = TshirtImage::whereNull('customer_id')->with('category')->latest()->paginate(15);

        // return view('tshirt_images.admin_index', compact('images'));

        $images = TshirtImage::whereNull('customer_id')
            ->with('category')
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('admin.tshirt-images.index', compact('images'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.tshirt-images.create', compact('categories'));
    }

    public function store(TshirtImageFormRequest $request)
    {
        // $validated = $request->validated();
        // $user = Auth::user();

        // $filename = null;
        // if ($request->hasFile('image_file')) {
        //     $file = $request->file('image_file');
        //     $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        //     if ($user->isCustomer()) {
        //         // Ficheiros privados de clientes ficam fora do disco publico.
        //         Storage::disk('local')->putFileAs('tshirt_images_private', $file, $filename);
        //     } else {
        //         // Imagens de catalogo ficam disponiveis no disco publico.
        //         Storage::disk('public')->putFileAs('tshirt_images', $file, $filename);
        //     }
        // }

        // TshirtImage::create([
        //     'name' => $validated['name'],
        //     'description' => $validated['description'] ?? null,
        //     'category_id' => $user->isCustomer() ? null : ($validated['category_id'] ?? null),
        //     'customer_id' => $user->isCustomer() ? $user->id : null,
        //     'image_url' => $filename,
        // ]);

        // return redirect()->route('tshirt-images.index')->with('alert-success', 'Design added successfully!');

         $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('tshirt_images', 'public');
        
        TshirtImage::create([
            'customer_id' => null,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'image_url' => basename($path),
        ]);

        return redirect()->route('admin.tshirt-images.index')->with('success', 'T-shirt image created.');
    }

    public function edit(TshirtImage $tshirtImage)
    {
        if ($tshirtImage->customer_id !== null) abort(404);
        $categories = Category::orderBy('name')->get();
        return view('admin.tshirt-images.edit', compact('tshirtImage', 'categories'));
    }

    public function update(Request $request, TshirtImage $tshirtImage)
    {
        if ($tshirtImage->customer_id !== null) abort(404);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete('tshirt_images/' . $tshirtImage->image_url);
            $path = $request->file('image')->store('tshirt_images', 'public');
            $data['image_url'] = basename($path);
        }

        $tshirtImage->update($data);
        return redirect()->route('admin.tshirt-images.index')->with('success', 'T-shirt image updated.');
    }

    public function destroy(TshirtImage $tshirtImage)
    {
        // $user = Auth::user();
        // if ($user->isCustomer() && $tshirtImage->customer_id !== $user->id) {
        //     abort(403);
        // }

        // // O registo e apagado logicamente; o ficheiro fisico e removido aqui.
        // if ($tshirtImage->customer_id) {
        //     Storage::disk('local')->delete("tshirt_images_private/{$tshirtImage->image_url}");
        // } else {
        //     Storage::disk('public')->delete("tshirt_images/{$tshirtImage->image_url}");
        // }

        // $tshirtImage->delete();

        // return back()->with('alert-success', 'Design asset discarded from inventory records.');

        if ($tshirtImage->customer_id !== null) abort(404);
        
        Storage::disk('public')->delete('tshirt_images/' . $tshirtImage->image_url);
        $tshirtImage->delete();
        return redirect()->route('admin.tshirt-images.index')->with('success', 'T-shirt image deleted.');
    }

    public function streamPrivateImage($filename)
    {
        $user = Auth::user();
        $tshirtImage = TshirtImage::where('image_url', $filename)->firstOrFail();

        // Um cliente so pode abrir os proprios ficheiros privados.
        if ($user->isCustomer() && $tshirtImage->customer_id !== $user->id) {
            abort(403, 'Unauthorized resource stream lookup.');
        }

        $path = "tshirt_images_private/{$filename}";
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($path));
    }
}
