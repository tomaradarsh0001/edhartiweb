<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyTransferredLesseeDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    // protected $fillable = [
    //     'property_master_id',
    //     'splited_property_detail_id',
    //     'process_of_transfer'
    // ];

}
