<?php

namespace App\Models;

use App\Models\leads;
use App\Models\User;
use App\Models\customers;
use App\Models\EstimateNote;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'estimate_no',
        'estimate_date',
        'customer_name',
        'customer_address',
        'mobile',
        'description',
        'subtotal',
        'discount',
        'gst_percent',
        'gst_amount',
        'transport_charges',
        'net_total',
        'estimatedby',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(estimateitems::class);
    }

    public function lead()
    {
        return $this->belongsTo(leads::class, 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function customer()
    {
        return $this->hasOne(customers::class, 'estimate_id');
    }
    public function notes()
    {
        return $this->hasMany(EstimateNote::class)
            ->orderBy('sort_order');
    }
}
