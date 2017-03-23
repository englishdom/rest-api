<?php

namespace User\Transformer;

use League\Fractal\TransformerAbstract;
use User\Entity\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $entity)
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
        ];
    }
}
