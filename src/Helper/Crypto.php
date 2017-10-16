<?php

namespace App\Helper;

class Crypto {
    const METHOD = 'aes-256-ctr';
    const ALGO = 'sha256';

    public static function encrypt($string, $key, $encode = false) {
        list($eKey, $aKey) = self::splitKeys($key);
        $nSize = openssl_cipher_iv_length(self::METHOD);
        $n = openssl_random_pseudo_bytes($nSize);
        $ct = $n.openssl_encrypt($string, self::METHOD, $eKey, OPENSSL_RAW_DATA, $n);
        if($encode) $ct = base64_encode($ct);
        $m = hash_hmac(self::ALGO, $ct, $aKey, true);
        if($encode) return base64_encode($m.$ct);
        return $m.$ct;
    }

    public static function decrypt($string, $key, $encoded = false) {
        list($eKey, $aKey) = self::splitKeys($key);
        if($encoded) {
            $string = base64_decode($string, true);
            if(!$string) throw new \Exception('Encryption failure (#1)');
        }
        $hs = mb_strlen(hash(self::ALGO, '', true), '8bit');
        $m = mb_substr($string, 0, $hs, '8bit');
        $ct = mb_substr($string, $hs, null, '8bit');
        $c = hash_hmac(self::ALGO, $ct, $aKey, true);
        if(!self::hashEquals($m, $c)) throw new \Exception('Encryption failure (#2)');
        if($encoded) {
            $ct = base64_decode($ct, true);
            if(!$ct) throw new \Exception('Encryption failure (#3)');
        }
        $nSize = openssl_cipher_iv_length(self::METHOD);
        $n = mb_substr($ct, 0, $nSize, '8bit');
        $ct = mb_substr($ct, $nSize, null, '8bit');
        return openssl_decrypt($ct, self::METHOD, $eKey, OPENSSL_RAW_DATA, $n);
    }

    private static function splitKeys($key) {
        return [
            hash_hmac(self::ALGO, 'ENCRYPTION', $key, true),
            hash_hmac(self::ALGO, 'AUTHENTICATION', $key, true)
        ];
    }

    private static function hashEquals($a, $b) {
        if(function_exists('hash_equals')) return hash_equals($a, $b);
        $n = openssl_random_pseudo_bytes(32);
        return hash_hmac(self::ALGO, $a, $n) === hash_hmac(self::ALGO, $b, $n);
    }
}