<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentService extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishmentUuid',
        'serviceId',
    ];
}
