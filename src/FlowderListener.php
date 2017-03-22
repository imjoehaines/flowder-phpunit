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
     * Initialise the Flowder instance
     *
     * We only need this until https://github.com/sebastianbergmann/phpunit/issues/1873
     * is fixed, then the Flowder instance can be configured through the phpunit.xml
     * and injected into the constructor
     *
     * @param Flowder $flowder
     * @return void
     */
    public static function bootstrap(Flowder $flowder)
    {
        static::$flowder = $flowder;
    }

    /**
     * NB: Until https://github.com/sebastianbergmann/phpunit/issues/1873 is fixed,
     * this cannot be used
     *
     * @param Flowder|null $flowder
     */
    public function __construct(Flowder $flowder = null)
    {
        static::$flowder = static::$flowder ?: $flowder;
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
        $this->checkIsInitialised();

        static::$flowder->loadFixtures();
    }

    /**
     * Check `bootstrap` has been called before we try and use the Flowder instance
     *
     * @return void
     * @throws LogicException when the Flowder instance hasn't been created
     */
    private function checkIsInitialised()
    {
        if (!isset(static::$flowder)) {
            throw new LogicException(
                'FlowderListener must be configured by calling FlowderListener::bootstrap before any tests run'
            );
        }
    }
}
