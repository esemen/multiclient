<?php

namespace Esemen\MultiClient;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $casts = [
        'id' => 'string',
        'options' => 'array'
    ];

    protected $fillable = ['name', 'options'];

    public function domains() {
        return $this->hasMany('Esemen\MultiClient\Models\Domain');
    }
}
