<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Warranty extends Model
{
    //
    protected $fillable = [
        'serial_number',
        'name',
        'location',
        'longitude',
        'latitude',
        'description',
        'status',
    ];


    public function getCreatedAtAttribute($value)
    {

        return date('m/d/Y', strtotime($this->attributes['created_at']));
    }

    public function getStatusAttribute($value)
    {
        if ($value == null || $value == 0) {
            $value = 'Not Fixed';
        } elseif ($value == 1) {
            $value = 'Fixed';
        } elseif ($value == 2) {
            $value = 'Void';
        }

        return $value;
    }
}
