<?php
declare(strict_types=1);

namespace GreenLinks\Psr16Adapter;

use GreenLinks\Psr16Adapter\Exception\InvalidArgumentException;
use GreenLinks\Psr16Adapter\Exception\GeneralException;

use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;

use DateInterval;
use Throwable;

use function get_class;
use function is_int;
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
        if (is_string($key)) {
            if (
                ( $ttl instanceof DateInterval )
                || ( is_int($ttl) &&  ($ttl > 0) )
                || ( null === $ttl )
            ) {
                try {
                    $item = $this
                        ->pool
                        ->getItem($key)
                        ->set($value)
                        ->expiresAfter($ttl);

                    return $this->pool->save($item);
                } catch (Throwable $e) {
                    throw new GeneralException(sprintf(
                        'Could not set value to cache with key "%s".',
                        $key
                    ));
                }
            }

            throw new InvalidArgumentException(
                sprintf(
                    '%s::%s expects third parameter to be an integer, DateInterval, or null, got "%s".',
                    __CLASS__,
                    __FUNCTION__,
                    gettype($key)
                )
            );
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
