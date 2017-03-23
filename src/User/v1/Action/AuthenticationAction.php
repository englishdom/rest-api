<?php

namespace User\Action;

use Common\Action\ActionInterface;
use Common\Container\ConfigInterface;
use Common\Exception\RuntimeException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use User\Entity\User;
use User\Transformer\UserTransformer;
use Zend\Http\Response;

class AuthenticationAction implements ActionInterface
{
    const RESOURCE_NAME = 'user';
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * AuthenticationAction constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }


    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $user = (new User())
            ->setId(1)
            ->setName('Test');

        $item = new Item($user, new UserTransformer(), $this->getResourceName());

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200)
            ->withAttribute(self::META, ['jwt' => $this->generateToken($user)]);

        return $delegate->process($request);
    }

    /**
     * @inheritdoc
     */
    public function getResourceName(): string
    {
        return self::RESOURCE_NAME;
    }

    /**
     * Generate token
     * @param User $user
     * @return string
     * @throws RuntimeException
     */
    private function generateToken(User $user): string
    {
        $signerName = $this->config->get('jwt.signer');
        $signature = $this->config->get('jwt.signature');
        if ($signerName == 'HMAC' && $signature == 256) {
            $signer = new Sha256();
        } else {
            throw new RuntimeException('Signer did not set in config');
        }

        $token = (new Builder())->setIssuer($this->config->get('host'))
            ->setAudience(md5('mobile phone'))
            ->setId(session_id())
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->set('uid', $user->getId())
            ->sign($signer, $this->config->get('jwt.secret-key'))
            ->getToken();

        return $token->getPayload();
    }
}
