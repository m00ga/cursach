<?php

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class JWT
{
    static function encode($payload)
    {
        $headers = ['alg' => 'HS256', 'typ' => "JWT"];
        $headers_enc = base64url_encode(json_encode($headers));

        $payload_enc = base64url_encode(json_encode($payload));
        $signature = hash_hmac("sha256", "$headers_enc.$payload_enc", SECRET_KEY, true);
        $sig_encoded = base64url_encode($signature);

        return "$headers_enc.$payload_enc.$sig_encoded";
    }

    static function verify($token)
    {
        $tokenParts = explode('.', $token);
        if(count($tokenParts) != 3) {
            return false;
        }
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature = $tokenParts[2];

        $payload_decoded = json_decode($payload, true);
        if(isset($payload_decoded['exp'])) {
            if ($payload_decoded['exp'] - time() < 0) {
                return false;
            }
        }
        $sig = hash_hmac('sha256', "$tokenParts[0].$tokenParts[1]", SECRET_KEY, true);
        $sig_enc = base64url_encode($sig);

        if($signature === $sig_enc) {
            return true;
        }else {
            return false;
        }
    }

    static function decode($token)
    {
        if(self::verify($token) == false){
            return false;
        }

        $payload = base64_decode(explode('.', $token)[1]);
        return json_decode($payload, true);
    }
}
