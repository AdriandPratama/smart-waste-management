<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteReport extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'latitude',
        'longitude',
        'status',
        'confidence_score',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'confidence_score' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ReportImage::class, 'report_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(ReportStatusHistory::class, 'report_id');
    }
}
