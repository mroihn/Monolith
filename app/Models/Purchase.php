<?php

namespace App\Models;

use App\Models\Film;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    protected $fillable = [
        'id',
        'user_id',
        'film_id'
    ];

    /**
     * Get all of the comments for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    
    public function films()
    {
        return $this->belongsTo(Film::class,'film_id','id');
    }

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
