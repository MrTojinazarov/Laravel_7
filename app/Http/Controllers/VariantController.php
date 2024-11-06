<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function destroy($id)
    {
        $variant = Variant::findOrFail($id);
        $variant->delete();

        return redirect()->back()->with('success', 'Variant muvaffaqiyatli o\'chirildi.');
    }
}
