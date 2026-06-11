<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Requests\PriceFormRequest;

class PriceController extends Controller
{
    public function edit()
    {
        $price = Price::first() ?? new Price();

        return view('prices.edit', compact('price'));
    }

    public function update(PriceFormRequest $request)
    {
        $price = Price::first();

        if (! $price) {
            $price = new Price();
        }

        $price->fill($request->validated());
        $price->save();

        return redirect()->route('prices.edit')->with('alert-success', 'Global operational platform configurations updated.');
    }
}
