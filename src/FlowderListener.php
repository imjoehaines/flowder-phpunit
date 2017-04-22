<?php

namespace Imjoehaines\Flowder\PhpUnit;

use LogicException;
use PHPUnit_Framework_Test;
use Imjoehaines\Flowder\Flowder;
use PHPUnit\Framework\BaseTestListener;

final class FlowderListener extends BaseTestListener
{
    /**
     * @var Flowder
     */
    private static $flowder;

    /**
     * @var mixed
     */
    private static $thingToLoad;

    /**
     * Initialise the Flowder instance
     *
     * We only need this until https://github.com/sebastianbergmann/phpunit/issues/1873
     * is fixed, then the Flowder instance can be configured through the phpunit.xml
     * and injected into the constructor
     *
     * @param Flowder $flowder
     * @param mixed $thingToLoad
     * @return void
     */
    public static function bootstrap(Flowder $flowder, $thingToLoad)
    {
        static::$flowder = $flowder;
        static::$thingToLoad = $thingToLoad;
    }

    /**
     * NB: Until https://github.com/sebastianbergmann/phpunit/issues/1873 is fixed,
     * this cannot be used
     *
     * @param Flowder|null $flowder
     */
    public function __construct(Flowder $flowder = null, $thingToLoad = null)
    {
        static::$flowder = static::$flowder ?: $flowder;
        static::$thingToLoad = static::$thingToLoad ?: $thingToLoad;
    }

    /**
     * Load fixtures before each test runs
     *
     * @param PHPUnit_Framework_Test $test
     * @return void
     * @throws LogicException when `bootstrap` hasn't been called
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        static::checkIsInitialised();

        static::$flowder->loadFixtures(static::$thingToLoad);
    }

    /**
     * Load fixtures manually rather than via the PHPUnit Listener
     *
     * @param mixed $thingToLoad
     * @return void
     */
    public static function loadFixtures($thingToLoad)
    {
        static::checkIsInitialised();

        static::$flowder->loadFixtures($thingToLoad);
    }

    /**
     * Check `bootstrap` has been called before we try and use the Flowder instance
     *
     * @return void
     * @throws LogicException when the Flowder instance hasn't been created
     */
    private static function checkIsInitialised()
    {
        if (!isset(static::$flowder, static::$thingToLoad)) {
            throw new LogicException(
                'FlowderListener must be configured by calling FlowderListener::bootstrap before any tests run'
            );
        }
    }
}
