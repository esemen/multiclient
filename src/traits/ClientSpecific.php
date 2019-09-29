<?php

namespace Esemen\MultiClient;

trait ClientSpecific
{
    public static function bootClientSpecific()
    {
        // Add client information on saving
        static::saving(function ($model) {
            // Configuration control
            if (config('multiclient.active')) {
                if (!$model->client_id) {
                    $model->client_id = App('Client')->id;
                }
            }
        });

        static::addGlobalScope(new ClientScope);
    }

    // Fetch only records in active client
    public static function scopeInClient($query) {
        return $query->where('client_id',App('Client')->id);
    }

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }
}