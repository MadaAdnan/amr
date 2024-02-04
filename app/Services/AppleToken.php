<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;

class AppleToken
{
    protected Configuration $jwtConfig;

    public function __construct(Configuration $jwtConfig)
    {
        $this->jwtConfig = $jwtConfig;
    }

    /**
     * Generates the client_secret for Sign-in with Apple on iOS (iOS platform)
     * or on the web (Android platform) based on the value of $useBundleId.
     *
     * @param bool $useBundleId Whether to use the App bundle ID for iOS or the Service ID for the web.
     * @see https://bannister.me/blog/generating-a-client-secret-for-sign-in-with-apple-on-each-request
     *
     * @return string
     */
    public function generateClientSecret(): string
    {
        $now = CarbonImmutable::now('UTC');

        $relatedTo = env("APPLE_APP_CLIENT_ID");
        
        $token = $this->jwtConfig->builder()
        ->issuedBy(config('services.apple.team_id'))
        ->issuedAt($now)
        ->expiresAt($now->addHour())
        ->permittedFor('https://appleid.apple.com')
        ->relatedTo($relatedTo)
        ->withHeader('kid', config('services.apple.key_id'))
        ->withHeader('sub', env('APPLE_APP_CLIENT_ID'))
        ->withHeader('content-type', 'application/x-www-form-urlencoded')
        ->getToken(new Sha256(), $this->jwtConfig->signingKey());

        return $token->toString();
    }
}