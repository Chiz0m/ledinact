<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    //
    protected $fillable = [
        'purchase_order_id',
        'serial_number'
    ];

    public function getCreatedAtAttribute($value)
    {
        return date('m/d/Y', strtotime($this->attributes['created_at']));
    }

    public function getWarrantyStartedAttribute($value)
    {
        if ($value == NULL) {
            return 'Not Activated';
        } else {
            return date('m/d/Y', strtotime($this->attributes['warranty_started']));
        }
    }
}
