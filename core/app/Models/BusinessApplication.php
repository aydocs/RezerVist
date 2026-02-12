<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // Added for the category relationship

class BusinessApplication extends Model
{
    use \App\Traits\LogsActivity;
    protected $fillable = [
        'user_id',
        'business_name',
        'category_id',
        'description',
        'address',
        'phone',
        'email',
        'trade_registry_no',
        'tax_id',
        'trade_registry_document',
        'tax_document',
        'license_document',
        'id_document',
        'bank_document',
        'status', // pending, approved, rejected
        'admin_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
