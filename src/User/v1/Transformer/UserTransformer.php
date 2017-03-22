<?php

namespace User\Transformer;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform($entity)
    {
        return $entity;
    }
}
