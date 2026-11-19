<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_date',
        'login_time',
        'checkin_latitude',
        'checkin_longitude',
        'checkin_address',
        'logout_time',
        'checkout_latitude',
        'checkout_longitude',
        'checkout_address',
        'last_activity_at',
        'ip_address',
        'user_agent',
        'device_info',
        'source',
        'status',
        'login_count',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'last_activity_at' => 'datetime',
        'login_count' => 'integer',
        'checkin_latitude' => 'float',
        'checkin_longitude' => 'float',
        'checkout_latitude' => 'float',
        'checkout_longitude' => 'float',
    ];

    protected $appends = [
        'formatted_login_time',
        'formatted_logout_time',
        'duration',
        'google_maps_checkin_url',
        'google_maps_checkout_url',
    ];

    public function getGoogleMapsCheckinUrlAttribute()
    {
        if ($this->checkin_latitude && $this->checkin_longitude) {
            return "https://www.google.com/maps?q={$this->checkin_latitude},{$this->checkin_longitude}";
        }
        return null;
    }

    public function getGoogleMapsCheckoutUrlAttribute()
    {
        if ($this->checkout_latitude && $this->checkout_longitude) {
            return "https://www.google.com/maps?q={$this->checkout_latitude},{$this->checkout_longitude}";
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', Carbon::today());
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function getFormattedLoginTimeAttribute()
    {
        if (empty($this->login_time)) {
            return '—';
        }

        try {
            return Carbon::createFromFormat('H:i:s', $this->login_time)->format('h:i A');
        } catch (\Throwable $e) {
            try {
                return Carbon::parse($this->login_time)->format('h:i A');
            } catch (\Throwable $ex) {
                return $this->login_time;
            }
        }
    }

    public function getFormattedLogoutTimeAttribute()
    {
        if (empty($this->logout_time)) {
            return '—';
        }

        try {
            return Carbon::createFromFormat('H:i:s', $this->logout_time)->format('h:i A');
        } catch (\Throwable $e) {
            try {
                return Carbon::parse($this->logout_time)->format('h:i A');
            } catch (\Throwable $ex) {
                return $this->logout_time;
            }
        }
    }

    public function getDurationAttribute()
    {
        if (empty($this->login_time)) {
            return '—';
        }

        try {
            $dateStr = $this->attendance_date ? $this->attendance_date->format('Y-m-d') : date('Y-m-d');
            $start = Carbon::parse($dateStr . ' ' . $this->login_time, 'Asia/Kolkata');
            
            if (!empty($this->logout_time)) {
                $end = Carbon::parse($dateStr . ' ' . $this->logout_time, 'Asia/Kolkata');
            } elseif ($this->last_activity_at) {
                $end = Carbon::parse($this->last_activity_at)->setTimezone('Asia/Kolkata');
            } else {
                $end = Carbon::now('Asia/Kolkata');
            }

            if ($end->lessThan($start)) {
                return '—';
            }

            $diffHours = $start->diffInHours($end);
            $diffMins = $start->diffInMinutes($end) % 60;

            return "{$diffHours}h {$diffMins}m";
        } catch (\Throwable $e) {
            return '—';
        }
    }
}
