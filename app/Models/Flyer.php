<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Flyer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function layers() {
        return $this->hasMany('App\Models\FlyerTemplate');
    }
}
