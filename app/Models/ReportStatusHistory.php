<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportStatusHistory extends Model
{
    protected $fillable = [
        'report_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    public function wasteReport()
    {
        return $this->belongsTo(WasteReport::class, 'report_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
