<?php
declare(strict_types=1);

namespace GreenLinks\Psr16Adapter\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements \Psr\SimpleCache\InvalidArgumentException
{
}
