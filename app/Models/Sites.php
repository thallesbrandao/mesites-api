<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'domain',
        'ftp',
        'ftpUser',
        'ftpPass',
        'ftpDir',
        'preview',
        'config_name',
        'config_email',
        'config_description',
    ];
    
}
