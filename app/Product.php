<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUCTO_DISPONIBLE = 'dispobile';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'descripcion',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];
    public function estaDisponible(){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    } 

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    
}
