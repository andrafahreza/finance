<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $table     = 'source';
    public $primaryKey   = 'id';
    protected $keyType   = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_user',
        'name',
    ];

    public function user(){
        return $this->belongsTo(User::class, "id_user");
    }

    public function income()
    {
        return $this->hasMany(Income::class, "id_source");
    }
}
