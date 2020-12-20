<?php
declare(strict_types=1);

namespace GreenLinks\Psr16Adapter\Exception;

use Psr\SimpleCache\CacheException;

use Exception;

class GeneralException extends Exception implements CacheException
{
}
