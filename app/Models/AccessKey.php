<?php

namespace App\Models;

use App\Services\OutlineVPN\ApiAccessKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessKey extends Model
{
    protected $fillable = [
        'server_id',
        'api_id',
        'name',
        'password',
        'method',
        'access_url',
        'port',
        'data_limit',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function(AccessKey $accessKey) {
            $api = api($accessKey->server->api_url);
            $newKeyRequest = $api->createKey();

            if (! $newKeyRequest->succeed)
                $newKeyRequest->throw();

            $outlineAccessKey = ApiAccessKey::fromObject($newKeyRequest->result);
            $renameRequest = $api->renameKey($outlineAccessKey->id, $accessKey->name);

            if (! $renameRequest->succeed)
                $renameRequest->throw();

            $accessKey->api_id = $outlineAccessKey->id;
            $accessKey->password = $outlineAccessKey->password;
            $accessKey->method = $outlineAccessKey->method;
            $accessKey->port = $outlineAccessKey->port;
            $accessKey->data_limit = $outlineAccessKey->dataLimitInBytes;
            $accessKey->access_url = $outlineAccessKey->accessUrl;
        });

        static::updating(function(AccessKey $accessKey) {
            $renameRequest = api($accessKey->server->api_url)->renameKey($accessKey->api_id, $accessKey->name);

            if (! $renameRequest->succeed)
                $renameRequest->throw();
        });

        static::deleting(function(AccessKey $accessKey) {
            $deleteKeyRequest = api($accessKey->server->api_url)->deleteKey($accessKey->api_id);

            if (! $deleteKeyRequest->succeed) {
                $deleteKeyRequest->throw();
            }
        });
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
