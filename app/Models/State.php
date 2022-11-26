<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class State extends Model
{
    use HasFactory;
    protected $primaryKey = "state_id";

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
}
