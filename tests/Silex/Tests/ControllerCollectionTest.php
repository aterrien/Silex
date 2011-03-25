<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Silex\Tests;

use Silex\Application;
use Silex\Controller;
use Silex\ControllerCollection;
use Silex\Exception\ControllerFrozenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * ControllerCollection test cases.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 */
class ControllerCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRouteCollectionWithNoRoutes()
    {
        $routes = new RouteCollection();
        $controllers = new ControllerCollection($routes);

        $this->assertEquals(0, count($routes->all()));
        $controllers->flush();
        $this->assertEquals(0, count($routes->all()));
    }

    public function testGetRouteCollectionWithRoutes()
    {
        $routes = new RouteCollection();
        $controllers = new ControllerCollection($routes);
        $controllers->add(new Controller(new Route('/foo')));
        $controllers->add(new Controller(new Route('/bar')));

        $this->assertEquals(0, count($routes->all()));
        $controllers->flush();
        $this->assertEquals(2, count($routes->all()));
    }

    public function testControllerFreezing()
    {
        $routes = new RouteCollection();
        $controllers = new ControllerCollection($routes);

        $fooController = new Controller(new Route('/foo'));
        $fooController->setRouteName('foo');
        $controllers->add($fooController);

        $barController = new Controller(new Route('/bar'));
        $barController->setRouteName('bar');
        $controllers->add($barController);

        $controllers->flush();

        try {
            $fooController->setRouteName('foo2');
            $this->fail();
        } catch (ControllerFrozenException $e) {
        }

        try {
            $barController->setRouteName('bar2');
            $this->fail();
        } catch (ControllerFrozenException $e) {
        }
    }
}