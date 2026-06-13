<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\OrderItem;
use App\Models\Price;
use App\Models\TshirtImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomTshirtController extends Controller
{
    protected function ensureCustomerAccess(): ?RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to create a customized T-shirt.');
        }

        abort_unless(auth()->user()->isCustomer(), 403, 'Only customer accounts can access customization.');

        return null;
    }

    public function create(): View|RedirectResponse
    {
        if ($redirect = $this->ensureCustomerAccess()) {
            return $redirect;
        }

        $colors = Color::orderBy('name')->get();
        $priceConfig = Price::first();

        return view('customize.create', [
            'colors' => $colors,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
            'customPrice' => $priceConfig?->unit_price_own ?? 0,
            'customDiscountPrice' => $priceConfig?->unit_price_own_discount ?? ($priceConfig?->unit_price_own ?? 0),
            'discountThreshold' => $priceConfig?->qty_discount ?? null,
        ]);
    }

    public function library(): View|RedirectResponse
    {
        if ($redirect = $this->ensureCustomerAccess()) {
            return $redirect;
        }

        $images = TshirtImage::query()
            ->where('customer_id', auth()->id())
            ->where(function ($builder) {
                $builder->where('custom', true)
                    ->orWhere('custom', 1)
                    ->orWhereNotNull('customer_id');
            })
            ->latest()
            ->paginate(12);

        $colors = Color::orderBy('name')->get();
        $priceConfig = Price::first();

        return view('customize.library', [
            'images' => $images,
            'colors' => $colors,
            'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
            'customPrice' => $priceConfig?->unit_price_own ?? 0,
            'customDiscountPrice' => $priceConfig?->unit_price_own_discount ?? ($priceConfig?->unit_price_own ?? 0),
            'discountThreshold' => $priceConfig?->qty_discount ?? null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->ensureCustomerAccess()) {
            return $redirect;
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'design_image' => ['required', 'image', 'max:4096'],
            'color_code' => ['required', 'exists:colors,code'],
            'size' => ['required', 'in:XS,S,M,L,XL'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $file = $request->file('design_image');
        $filename = 'custom_' . now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        Storage::disk('private')->putFileAs('tshirt_images_private', $file, $filename);

        $design = TshirtImage::create([
            'customer_id' => auth()->id(),
            'category_id' => null,
            'name' => $validated['name'] ?: 'Custom T-Shirt',
            'description' => 'Uploaded custom design',
            'image_url' => $filename,
            'custom' => true,
        ]);

        $priceConfig = Price::first();
        $qty = (int) $validated['qty'];
        $unitPrice = $this->getCustomUnitPrice($priceConfig, $qty);

        $key = "custom_{$design->id}_{$validated['color_code']}_{$validated['size']}";
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
            $cart[$key]['unit_price'] = $this->getCustomUnitPrice($priceConfig, $cart[$key]['qty']);
            $cart[$key]['sub_total'] = $cart[$key]['qty'] * $cart[$key]['unit_price'];
        } else {
            $cart[$key] = [
                'type' => 'custom',
                'is_custom' => true,
                'id' => $design->id,
                'tshirt_image_id' => $design->id,
                'name' => $design->name,
                'image_url' => $design->image_url,
                'color_code' => $validated['color_code'],
                'size' => $validated['size'],
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'sub_total' => $qty * $unitPrice,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Custom T-shirt added to cart.');
    }

    public function addExistingToCart(Request $request, TshirtImage $tshirtImage): RedirectResponse
    {
        if ($redirect = $this->ensureCustomerAccess()) {
            return $redirect;
        }

        $this->ensureOwnCustomImage($tshirtImage);

        $validated = $request->validate([
            'color_code' => ['required', 'exists:colors,code'],
            'size' => ['required', 'in:XS,S,M,L,XL'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $this->migrateLegacyCustomImageToPrivate($tshirtImage);

        $priceConfig = Price::first();
        $qty = (int) $validated['qty'];
        $unitPrice = $this->getCustomUnitPrice($priceConfig, $qty);
        $key = "custom_{$tshirtImage->id}_{$validated['color_code']}_{$validated['size']}";
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
            $cart[$key]['unit_price'] = $this->getCustomUnitPrice($priceConfig, $cart[$key]['qty']);
            $cart[$key]['sub_total'] = $cart[$key]['qty'] * $cart[$key]['unit_price'];
        } else {
            $cart[$key] = [
                'type' => 'custom',
                'is_custom' => true,
                'id' => $tshirtImage->id,
                'tshirt_image_id' => $tshirtImage->id,
                'name' => $tshirtImage->name,
                'image_url' => $tshirtImage->image_url,
                'color_code' => $validated['color_code'],
                'size' => $validated['size'],
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'sub_total' => $qty * $unitPrice,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Custom image added to cart again.');
    }

    public function destroyLibraryImage(TshirtImage $tshirtImage): RedirectResponse
    {
        if ($redirect = $this->ensureCustomerAccess()) {
            return $redirect;
        }

        $this->ensureOwnCustomImage($tshirtImage);
        $this->migrateLegacyCustomImageToPrivate($tshirtImage);

        $hasOrderHistory = OrderItem::query()
            ->where('tshirt_image_id', $tshirtImage->id)
            ->exists();

        if (! $hasOrderHistory) {
            Storage::disk('private')->delete('tshirt_images_private/' . $tshirtImage->image_url);
            Storage::disk('public')->delete('tshirt_images/' . $tshirtImage->image_url);
        }

        $cart = collect(session('cart', []))
            ->reject(fn ($item) => (int) ($item['tshirt_image_id'] ?? 0) === (int) $tshirtImage->id)
            ->all();

        session()->put('cart', $cart);

        $tshirtImage->delete();

        return redirect()->route('customize.library')->with('success', 'Custom image removed from your library.');
    }

    public function streamPrivateImage(TshirtImage $tshirtImage): StreamedResponse
    {
        abort_unless(auth()->check() && auth()->user()->isCustomer(), 403, 'Only customers can access custom design images.');
        $this->ensureOwnCustomImage($tshirtImage);
        $privatePath = $this->migrateLegacyCustomImageToPrivate($tshirtImage);

        abort_unless(Storage::disk('private')->exists($privatePath), 404, 'Image file not found.');

        return Storage::disk('private')->response($privatePath);
    }

    protected function getCustomUnitPrice(?Price $priceConfig, int $qty): float
    {
        if (! $priceConfig) {
            return 0;
        }

        if ($priceConfig->qty_discount && $qty >= $priceConfig->qty_discount) {
            return (float) $priceConfig->unit_price_own_discount;
        }

        return (float) $priceConfig->unit_price_own;
    }

    protected function ensureOwnCustomImage(TshirtImage $tshirtImage): void
    {
        abort_unless(
            $tshirtImage->customer_id === auth()->id() && ($tshirtImage->custom || $tshirtImage->customer_id !== null),
            403,
            'Unauthorized private image access.'
        );
    }

    protected function migrateLegacyCustomImageToPrivate(TshirtImage $tshirtImage): string
    {
        $privatePath = 'tshirt_images_private/' . $tshirtImage->image_url;
        $publicPath = 'tshirt_images/' . $tshirtImage->image_url;

        if (! Storage::disk('private')->exists($privatePath) && Storage::disk('public')->exists($publicPath)) {
            Storage::disk('private')->put($privatePath, Storage::disk('public')->get($publicPath));
            Storage::disk('public')->delete($publicPath);
        }

        return $privatePath;
    }
}
