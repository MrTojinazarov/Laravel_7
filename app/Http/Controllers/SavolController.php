<?php

namespace App\Http\Controllers;

use App\Models\Ovoz;
use App\Models\Savol;
use App\Models\Variant;
use Illuminate\Http\Request;

class SavolController extends Controller
{
    public function index()
    {
        $savols = Savol::with('variants')->get();
        return view('admin.survey', compact('savols'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $savol = new Savol();
        $savol->name = $request->name;
        $savol->is_active = $request->has('is_active'); 
        $savol->save();
    
        if ($request->has('variants')) {
            foreach ($request->variants as $variantName) {
                $variant = new Variant();
                $variant->name = $variantName;
                $variant->savol_id = $savol->id;
                $variant->save();
            }
        }
    
        return redirect()->back()->with('success', 'So\'rovnoma muvaffaqiyatli qo\'shildi.');
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'savol_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'variants' => 'required|array',
            'variants.*' => 'required|string|max:255',
        ]);

        $savol = Savol::findOrFail($id);
        $savol->update([
            'name' => $request->savol_name,
            'is_active' => $request->is_active ? true : false,
        ]);

        $savol->variants()->delete();

        foreach ($request->variants as $variantName) {
            Variant::create([
                'savol_id' => $savol->id,
                'name' => $variantName,
            ]);
        }

        return redirect()->route('survey.index')->with('success', 'So\'rovnoma muvaffaqiyatli yangilandi.');
    }

    public function destroy($id)
    {
        $savol = Savol::findOrFail($id);
        $savol->variants()->delete(); // Delete related variants
        $savol->delete(); // Delete the question

        return redirect()->route('survey.index')->with('success', 'So\'rovnoma muvaffaqiyatli o\'chirildi.');
    }
}
