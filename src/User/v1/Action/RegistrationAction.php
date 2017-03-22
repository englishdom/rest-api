<?php

namespace User\Action;

use Common\Action\ActionInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use User\Transformer\UserTransformer;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Http\Response;

class RegistrationAction implements ActionInterface
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * RegistrationAction constructor.
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
//        $select = new Select();
//        $select->columns(['*']);
//        $select->from('users');
//
//        $sql = $select->getSqlString($this->adapter->getPlatform());
//        $rows = $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);

        $user = [
            'id' => 1,
            'name' => 'Test',
        ];

        $request = $request->withAttribute(self::RESPONSE, new Item($user, new UserTransformer()))
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_201);

        return $delegate->process($request);
    }
}
