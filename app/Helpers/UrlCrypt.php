<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class UrlCrypt
{
    /**
     * Encrypt ID untuk URL dengan hasil yang lebih pendek
     */
    public static function encrypt($id)
    {
        try {
            // Use base64 encoding with custom key for shorter URLs
            $key = substr(hash('sha256', config('app.key')), 0, 16);
            $encrypted = openssl_encrypt($id, 'AES-128-ECB', $key);
            // Remove padding and make URL safe
            return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
        } catch (\Exception $e) {
            return $id; // Fallback ke ID asli jika gagal encrypt
        }
    }

    /**
     * Decrypt ID dari URL dengan dukungan format baru dan lama
     */
    public static function decrypt($encryptedId)
    {
        try {
            // Try new format first (shorter encryption)
            if (strlen($encryptedId) < 100) { // New format is much shorter
                $key = substr(hash('sha256', config('app.key')), 0, 16);
                // Restore padding and convert from URL safe
                $base64 = str_pad(strtr($encryptedId, '-_', '+/'), strlen($encryptedId) % 4, '=', STR_PAD_RIGHT);
                $encrypted = base64_decode($base64);
                $decrypted = openssl_decrypt($encrypted, 'AES-128-ECB', $key);
                if ($decrypted !== false && is_numeric($decrypted)) {
                    return $decrypted;
                }
            }
            
            // Fallback to old format (Laravel Crypt)
            return Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            // Jika gagal decrypt, coba gunakan sebagai ID langsung (untuk backward compatibility)
            return is_numeric($encryptedId) ? $encryptedId : null;
        }
    }

    /**
     * Generate unique hash untuk URL
     */
    public static function generateHash($id, $prefix = '')
    {
        return $prefix . hash('sha256', config('app.key') . $id . time()) . base64_encode($id);
    }

    /**
     * Extract ID dari hash
     */
    public static function extractFromHash($hash, $prefix = '')
    {
        if ($prefix && !str_starts_with($hash, $prefix)) {
            return null;
        }
        
        $hashWithoutPrefix = $prefix ? substr($hash, strlen($prefix)) : $hash;
        
        try {
            // Extract base64 part (last part after SHA256 hash)
            if (strlen($hashWithoutPrefix) > 64) { // SHA256 is 64 chars
                $base64Part = substr($hashWithoutPrefix, 64);
                return base64_decode($base64Part);
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }

    /**
     * Generate encrypted URL for route
     */
    public static function route($routeName, $id, $parameters = [])
    {
        $encryptedId = self::encrypt($id);
        return route($routeName, array_merge([$encryptedId], $parameters));
    }

    /**
     * Batch encrypt multiple IDs
     */
    public static function batchEncrypt(array $ids)
    {
        $encrypted = [];
        foreach ($ids as $id) {
            $encrypted[$id] = self::encrypt($id);
        }
        return $encrypted;
    }
    
    /**
     * Generate short URL for product detail
     * Format: /barang?id=encrypted_id (optimized for speed)
     */
    public static function shortRoute($id, $parameters = [])
    {
        $encryptedId = self::encrypt($id);
        $baseUrl = url('/barang');
        $queryParams = array_merge(['id' => $encryptedId], $parameters);
        return $baseUrl . '?' . http_build_query($queryParams);
    }
    
    /**
     * Generate multiple short URLs efficiently (batch processing)
     */
    public static function batchShortRoutes(array $ids)
    {
        $baseUrl = url('/barang');
        $urls = [];
        
        foreach ($ids as $id) {
            $encryptedId = self::encrypt($id);
            $urls[$id] = $baseUrl . '?id=' . $encryptedId;
        }
        
        return $urls;
    }
    
    /**
     * Generate short URL with custom base path
     */
    public static function shortUrl($id, $basePath = '/barang', $parameters = [])
    {
        $encryptedId = self::encrypt($id);
        $queryParams = array_merge(['id' => $encryptedId], $parameters);
        return url($basePath . '?' . http_build_query($queryParams));
    }
}