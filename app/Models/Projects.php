<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;

    protected $appends = ['url'];

    protected $fillable = ['user_id', 'name', 'config_logo', 'config_name', 'config_email', 'config_description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUrlAttribute()
    {
        return 'https://builder.meeventos.com.br/?token=' . $this->user->token . '&edit=' . $this->id;
    }
}
