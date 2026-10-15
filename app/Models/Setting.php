<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Préfixe pour le stockage en cache.
     */
    protected const CACHE_PREFIX = 'biofarm_setting_';

    /**
     * Récupère la valeur typée d'un paramètre.
     */
    public function getTypedValueAttribute(): mixed
    {
        return self::castValue($this->value, $this->type);
    }

    /**
     * Cast d'une valeur selon son type.
     */
    protected static function castValue(?string $value, string $type): mixed
    {
        if (is_null($value)) {
            return null;
        }

        $cleanValue = str_replace(',', '.', trim($value));

        return match ($type) {
            'integer', 'int' => (strpos($cleanValue, '.') !== false) ? (float) $cleanValue : (int) $cleanValue,
            'float', 'double', 'decimal' => (float) $cleanValue,
            'boolean', 'bool' => filter_var($cleanValue, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => json_decode($value, true),
            default => is_numeric($cleanValue) ? (float) $cleanValue : $value,
        };
    }

    /**
     * Récupère la valeur d'un paramètre avec mise en cache.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(self::CACHE_PREFIX . $key, function () use ($key, $default) {
            try {
                $setting = self::where('key', $key)->first();

                if (! $setting) {
                    return $default;
                }

                return self::castValue($setting->value, $setting->type ?? 'string');
            } catch (\Throwable $e) {
                return $default;
            }
        });
    }

    /**
     * Définit ou met à jour un paramètre et vide le cache.
     */
    public static function set(string $key, mixed $value, string $type = 'string', ?string $description = null): self
    {
        $stringValue = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'description' => $description ?? self::where('key', $key)->value('description'),
            ]
        );

        Cache::forget(self::CACHE_PREFIX . $key);

        return $setting;
    }
}
