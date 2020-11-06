<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    //
    protected $fillable = [
        'purchase_order_number',
        'customer_name',
        'destination_address',
        'phone',
    ];

    public function getActivationStatusAttribute($value)
    {
        if ($value == null) {
            return $value = 'Warranty is not Activated';
        } else {
            return date('m/d/Y', strtotime($this->attributes['activation_status']));
        }
    }
    public function getCreatedAtAttribute($value)
    {
        return date('m/d/Y', strtotime($this->attributes['created_at']));
    }
    public function getUpdatedAtAttribute($value)
    {
        return date('m/d/Y', strtotime($this->attributes['updated_at']));
    }
}
