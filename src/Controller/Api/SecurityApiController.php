<?php

declare(strict_types=1);

namespace Src\Controller\Api;

class SecurityApiController
{
    private $secretKey = 'ma_cle_de_test_123456_à_modifier_en_production';
    // Fonction pour générer le JWT

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    private function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function generateJwt($user)
    {

        // En-tête (Header)
        $header = json_encode([
            'alg' => 'HS256', // Algorithme de signature
            'typ' => 'JWT'    // Type de token
        ]);

        // Payload (les données)
        $payload = json_encode([
            'id' => $user['id'],      // Identifiant de l'utilisateur
            'name' => $user['name'],   // Nom d'utilisateur
            'email' => $user['email'],   // Rôle de l'utilisateur
            'iat' => time(),           // Date d'émission du token
            'exp' => time() + 3600     // Date d'expiration (1 heure = 3600 secondes)
        ]);

        // Encoder l'en-tête et le payload en base64
        $base64Header = $this->base64UrlEncode($header);
        $base64Payload =  $this->base64UrlEncode($payload);

        // Créer la signature
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $this->secretKey, true);
        $base64Signature =  $this->base64UrlEncode($signature);

        // Retourner le JWT (en-tête, payload, signature)
        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    public function verifyJwt($jwt)
    {
        // Diviser le JWT en ses trois parties
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return false;  // Le token est mal formé
        }

        list($base64Header, $base64Payload, $base64Signature) = $parts;

        // Décoder l'en-tête et le payload
        $header = json_decode($this->base64UrlDecode($base64Header), true);
        $payload = json_decode($this->base64UrlDecode($base64Payload), true);

        // Vérifier si l'en-tête contient bien l'algorithme HS256
        if ($header['alg'] !== 'HS256') {
            return false;  // L'algorithme ne correspond pas
        }

        // Vérifier l'expiration (si elle existe)
        if (isset($payload['exp']) && time() > $payload['exp']) {
            return false;  // Le token a expiré
        }

        $dataToSign = $base64Header . '.' . $base64Payload;
        $calculatedSignature = hash_hmac('sha256', $dataToSign, $this->secretKey, true);
        $base64CalculatedSignature = $this->base64UrlEncode($calculatedSignature);

        // Comparer la signature du token avec la signature recalculée
        if ($base64Signature !== $base64CalculatedSignature) {
            return false;  // Les signatures ne correspondent pas
        }

        // Si tout est OK, le token est valide
        return true;
    }

    public function decodeJwt($jwt)
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }

        list(, $base64Payload,) = $parts;
        $payload = json_decode($this->base64UrlDecode($base64Payload), true);

        return $payload;
    }

    public function getAuthenticatedUserIdFromToken(): ?int
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return null;
        }

        $token = $matches[1];

        if (!$this->verifyJwt($token)) {
            return null;
        }

        $decoded = $this->decodeJwt($token);
        if (!$decoded || empty($decoded['id'])) {
            return null;
        }

        return (int) $decoded['id'];
    }
}
