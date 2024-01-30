<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table     = 'category';
    public $primaryKey   = 'id';
    protected $keyType   = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_user',
        'name'
    ];

    public function user(){
        return $this->belongsTo(User::class, "id_user");
    }
}
