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
        'admission_day',
        'admission_time',
        'customer_class',
        'customer_id',
        'box_name',
        'junior_flg',
        'holiday_flg',
        'delete_flg'
    ];
}
