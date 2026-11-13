<?php

namespace App\Models;

use App\Models\estimate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimate_id',
        'note',
        'sort_order',
    ];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }
}
