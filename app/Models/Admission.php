<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    //
      protected $table = 'admission';

    //   /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var list<string>
    //  */
    protected $fillable = [
        'admission_no',
        'admission_day',
        'admission_time',
        'customer_class',
        'customer_id',
        'box_name',
        'qr_code',
        'billing_flg'
    ];
}
