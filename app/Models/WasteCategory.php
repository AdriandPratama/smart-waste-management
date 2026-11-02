<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class, 'category_id');
    }
}
