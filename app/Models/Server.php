<?php

namespace App\Models;

use App\Services\OutlineVPN\ApiClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Throwable;

class Server extends Model
{
    protected $fillable = [
        'api_url',
        'api_cert_sha256',
        'api_id',
        'name',
        'version',
        'hostname_for_new_access_keys',
        'port_for_new_access_keys',
        'is_metrics_enabled',
        'is_available',
        'api_created_at',
    ];

    protected $casts = [
        'api_created_at' => 'datetime',
        'is_metrics_enabled' => 'boolean',
        'is_available' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function(Server $server) {
            $api = new ApiClient($server->api_url);
            $serverInfoRequest = $api->server();

            if (! $serverInfoRequest->succeed)
                $serverInfoRequest->throw();

            $serverInfo = $serverInfoRequest->result;

            static::mapApiResult($server, $serverInfo);
        });

        static::retrieved(function(Server $server) {
            try {
                $api = new ApiClient($server->api_url);
                $maxRetry = 3;
                $try = 0;

                do {
                    try {
                        $serverInfoRequest = $api->server();
                        $serverInfo = $serverInfoRequest->result;

                        static::mapApiResult($server, $serverInfo);

                        break;
                    } catch (Throwable $_) {
                        $try++;
                    }
                } while ($try < $maxRetry);

                $server->is_available = $try < $maxRetry;
            } catch (Throwable $exception) {
                $server->is_available = false;
                // TODO: report error to sentry
            } finally {
                $server->saveQuietly();
            }
        });
    }

    public function keys(): HasMany
    {
        return $this->hasMany(AccessKey::class);
    }

    private static function mapApiResult(Server $server, object $apiResult): void
    {
        $server->api_id = $apiResult->serverId;
        $server->name = $apiResult->name;
        $server->version = $apiResult->version;
        $server->is_metrics_enabled = $apiResult->metricsEnabled;
        $server->api_created_at = now()->parse($apiResult->createdTimestampMs / 1000);
        $server->port_for_new_access_keys = $apiResult->portForNewAccessKeys;
        $server->hostname_for_new_access_keys = $apiResult->hostnameForAccessKeys;
    }
}
