<?php
declare(strict_types=1);

namespace GreenLinks\Psr16Adapter;

use Psr\SimpleCache\CacheInterface;

class Adapter implements CacheInterface
{
    /**
     * @return mixed
     */
    public function get($key, $default = null)
    {
    }

    public function set($key, $value, $ttl = null): bool
    {
    }

    public function delete($key): bool
    {
    }

    public function clear(): bool
    {
    }

    /**
     * @return iterable
     */
    public function getMultiple($keys, $default = null)
    {
    }

    public function setMultiple($values, $ttl = null): bool
    {
    }

    public function deleteMultiple($keys): bool
    {
    }

    public function has($key): bool
    {
    }
}
