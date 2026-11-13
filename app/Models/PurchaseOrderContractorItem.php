<?php

namespace App\Models;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderContractorItem extends Model
{
    use HasFactory;

     protected $fillable = [
        'purchase_order_id',
        'scope_of_work',
        'qty',
        'unit',
        'rate',
        'amount',
    ];


    public function purchaseOrder()
    {
        return $this->belongsTo(
            PurchaseOrder::class,
            'purchase_order_id'
        );
    }
}
