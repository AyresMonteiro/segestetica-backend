<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstablishmentService extends Model
{
    use HasFactory;
    use HasCompositePrimaryKey;

    protected $primaryKey = ['establishmentUuid', 'serviceId'];

    protected $fillable = [
        'establishmentUuid',
        'serviceId',
        'active',
    ];
}
