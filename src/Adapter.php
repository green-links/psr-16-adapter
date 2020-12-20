<?php
declare(strict_types=1);

namespace GreenLinks\Psr16Adapter;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

use Throwable;

use function get_class;
use function is_object;
use function is_string;
use function sprintf;

class Adapter implements CacheInterface
{
    private CacheItemPoolInterface $pool;

    public static function create($cache): CacheInterface
    {
        switch (true) {
            case $cache instanceof CacheItemPoolInterface:
                return new self($cache);

            case $cache instanceof CacheInterface:
                return $cache;

            default:
                throw new InvalidArgumentException(sprintf(
                    '%s::%s first argument must be an instance of %s or %s, got "%s".',
                    __CLASS__,
                    __FUNCTION__,
                    CacheItemPoolInterface::class,
                    CacheInterface::class,
                    is_object($cache) ? get_class($cache) : gettype($cache)
                ));
        }
    }

    /**
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_string($key)) {
            try {
                if ($this->pool->hasItem($key)) {
                    return $this->pool->getItem($key)->get();
                }
            } catch (Throwable $e) {
                throw new GeneralException(sprintf(
                    'Could not get value from cache with key "%s".',
                    $key
                ));
            }

            return $default;
        }

        throw new InvalidArgumentException(
            sprintf(
                '%s::%s expects first parameter to be a string, got "%s".',
                __CLASS__,
                __FUNCTION__,
                gettype($key)
            )
        );
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

    public function getCachePool(): CacheItemPoolInterface
    {
        return $this->pool;
    }

    private function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
    }
}
