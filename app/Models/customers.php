<?php

namespace App\Models;

use App\Models\estimate;
use App\Models\PurchaseOrder;
use App\Models\MaterialDispatch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'Mobile',
        'Location',
        'Area',
        'Product',
        'Total_values',
        'status',
        'lead_id',
        'estimate_id',
    ];

    public function materialItems()
    {
        return $this->hasMany(MaterialItem::class);
    }

    public function materialDispatches()
    {
        return $this->hasMany(MaterialDispatch::class, 'customer_id');
    }

    public function materialReturns()
    {
        return $this->hasMany(MaterialReturn::class, 'customer_id');
    }
    

    
    public function estimate()
    {
        return $this->belongsTo(estimate::class, 'estimate_id');
    }
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'site_id');
    }

    public function getFormattedTotalValueAttribute()
    {
        if (empty($this->Total_values)) {
            return '0.00';
        }

        $val = (string) $this->Total_values;
        if (str_contains($val, ',')) {
            return $val;
        }

        if (is_numeric($val)) {
            return number_format((float) $val, 2);
        }

        return $val;
    }
}
