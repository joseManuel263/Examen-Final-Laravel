<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemones extends Model{
    use HasFactory;
    protected $table = 'pokemones';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'tipo',
        'url_imagen',
        'hp',
        'defensa',
        'ataque',
        'rapidez',
        'create_at',
        'update_at',
        'id_user'
    ];
}
