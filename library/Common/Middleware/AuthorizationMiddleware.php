<?php

namespace Common\Action;

use Common\Container\ConfigInterface;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Lcobucci\JWT\Builder;

class AuthorizationMiddleware
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * AuthorizationMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $token = (new Builder())->setIssuer('https://from.site.com') // Configures the issuer (iss claim)
            ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
            ->set('uid', 1) // Configures a new claim, called "uid"
            ->sign(new Sha256(), 'testing')
            ->getToken(); // Retrieves the generated token

        echo $token;
    }
}
