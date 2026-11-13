<?php

namespace App\Models;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderTerm extends Model
{
    use HasFactory;

     protected $fillable = [
        'purchase_order_id',
        'term',
        'sort_order',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class,
            'purchase_order_id'
        );
    }
}
