<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $table     = 'income';
    public $primaryKey   = 'id';
    protected $keyType   = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'id_user',
        'id_source',
        'value',
        'note',
        'date',
    ];

    public function user(){
        return $this->belongsTo(User::class, "id_user");
    }

    public function source(){
        return $this->belongsTo(Source::class, "id_source");
    }
}
