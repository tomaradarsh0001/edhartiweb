<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IhcsApplicationsHistory extends Model
{
    use HasFactory;
    protected $table = 'ihcs_applications_histories';
    protected $guarded = [];
}
