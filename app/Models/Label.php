<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $table = 'setup_labels';

    protected $fillable = [
        'id', 'code', 'title', 'desc', 'ordering', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
