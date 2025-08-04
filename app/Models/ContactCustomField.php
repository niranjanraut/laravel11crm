<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactCustomField extends Model
{
    //
    protected $table = 'contact_custom_field';    
    protected $primaryKey = 'contact_custom_field_id';
    public $timestamps = false;
}
