<?php

namespace Esemen\MultiClient;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ['domain'];

    public function client() {
        return $this->belongsTo('Esemen\MultiClient\Client');
    }
}
