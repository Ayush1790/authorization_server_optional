<?php

use Phalcon\Mvc\Micro;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

$app = new Micro();
$app->get(
    '/token',
    function () use ($app) {
        // // Defaults to 'sha512'
        $signer  = new Hmac();

        // Builder object
        $builder = new Builder($signer);

        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        // Setup
        $builder
            ->setAudience('https://target.phalcon.io')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp
            ->setId('abcd123456789')                    // JTI id
            ->setIssuedAt($issued)                      // iat
            ->setIssuer('https://phalcon.io')           // iss
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject('my subject for this claim')   // sub
            ->setPassphrase($passphrase)                // password
        ;

        // Phalcon\Security\JWT\Token\Token object
        $tokenObject = $builder->getToken();
        return json_encode($tokenObject->getToken());
    }
);
$app->handle(
    $_SERVER["REQUEST_URI"]
);
