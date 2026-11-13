<?php

namespace App\Models;

use App\Models\customers;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderTerm;
use App\Models\PurchaseOrderContractorItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_no',
        'po_type',
        'company',
        'vendor_id',
        'contractor_name',
        'contractor_address',
        'site_id',
        'po_template',
        'po_date',
        'subtotal',
        'gst_percent',
        'gst_amount',
        'grand_total',
        'remarks',
        'created_by',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function customer()
    {
        return $this->belongsTo(customers::class, 'site_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    public function pocustomer()
    {
        return $this->belongsTo(customers::class, 'site_id');
    }
    public function contractorItems()
    {
        return $this->hasMany(PurchaseOrderContractorItem::class,'purchase_order_id');
    }

    public function terms()
    {
        return $this->hasMany(PurchaseOrderTerm::class,'purchase_order_id')->orderBy('sort_order');
    }
}
