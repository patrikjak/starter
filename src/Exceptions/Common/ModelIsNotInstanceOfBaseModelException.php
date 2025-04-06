<?php

namespace Patrikjak\Starter\Exceptions\Common;

use Exception;

class ModelIsNotInstanceOfBaseModelException extends Exception
{
    public function __construct(string $model, string $baseModel)
    {
        $message = sprintf(
            'The model %s is not an instance of the base model %s.',
            $model,
            $baseModel
        );

        parent::__construct($message);
    }
}
