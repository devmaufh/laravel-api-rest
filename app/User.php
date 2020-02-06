<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //Traits
    use Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';
    const USUARIO_REGULAR = 'false';
    const USUARIO_ADMINISTRADOR = 'true';

    protected $table = 'users';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    public function setNameAttribute($name){
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name){
        return ucwords($name);
        
    }

    public function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email);
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token',
    ];
    
    public function esVerificado(){
        return $this->verified == User::USUARIO_VERIFICADO;
    }
    public function esAdmin(){
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }
    public static function generarVerificationToken(){
        return str_random(100);
    }
}
