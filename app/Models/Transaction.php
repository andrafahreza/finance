<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table     = 'transaction';
    public $primaryKey   = 'id';
    protected $keyType   = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_user',
        'to_user',
        'id_category',
        'value',
        'note',
    ];

    public function user(){
        return $this->belongsTo(User::class, "id_user");
    }

    public function toUser(){
        return $this->belongsTo(User::class, "to_user");
    }

    public function category(){
        return $this->belongsTo(Category::class, "id_category");
    }
}
