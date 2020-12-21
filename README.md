# PSR-16 Adapter

Standalone adapter for making a PSR-6 cache behave like a PSR-16 cache.

## Usage

The adapter can be called with a PSR-6 or PSR-16 cache object.

    $cache = Adapter::create($psr6_or_psr16_cache);

If the adapter is passed a psr-16 cache, then it returns the same psr-16 cache object.
If the adapter is passed a psr-6 cache, then it returns an instance of itself.
If the adapter is passed anything else then an exception is thrown.
