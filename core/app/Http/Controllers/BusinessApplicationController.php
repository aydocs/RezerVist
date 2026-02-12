<?php

namespace App\Http\Controllers;

use App\Models\BusinessApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessApplicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'trade_registry_no' => 'required|string',
            'tax_id' => 'required|string',
            'trade_registry_document' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'tax_document' => 'required|file|mimes:pdf|max:5120',
            'license_document' => 'required|file|mimes:pdf|max:5120',
            'id_document' => 'required|file|mimes:pdf|max:5120',
            'bank_document' => 'required|file|mimes:pdf|max:5120',
            'terms_accepted' => 'required|accepted',
        ]);

        $documents = [];
        $documentFields = [
            'trade_registry_document',
            'tax_document',
            'license_document',
            'id_document',
            'bank_document'
        ];

        foreach ($documentFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('applications', 'public');
                $documents[$field] = $path;
            }
        }

        BusinessApplication::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            ...$request->except(['_token', 'terms_accepted', ...$documentFields]),
            ...$documents
        ]);

        return redirect()->back()->with('success', 'Başvurunuz başarıyla alındı! Ekibimiz belgelerinizi inceleyip en kısa sürede size dönüş yapacaktır.');
    }
}
