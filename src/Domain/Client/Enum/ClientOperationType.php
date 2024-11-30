<?php

namespace App\Domain\Client\Enum;

enum ClientOperationType: string
{
    case UPDATED = 'updated';
    case CREATED = 'created';
    case DELETED = 'deleted';
    case NOT_MODIFIED = 'not_modified';
}
