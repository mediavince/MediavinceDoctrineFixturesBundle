<?php

namespace Mediavince\DoctrineFixturesBundle\Tests\Common\DataFixtures;

use Mediavince\DoctrineFixturesBundle\Tests\TestCase;
use Mediavince\DoctrineFixturesBundle\Tests\Common\ContainerAwareFixture;
use Mediavince\DoctrineFixturesBundle\Common\DataFixtures\Loader;

class LoaderTest extends TestCase
{
    public function testShouldSetContainerOnContainerAwareFixture()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $loader    = new Loader($container);
        $fixture   = new ContainerAwareFixture();

        $loader->addFixture($fixture);

        $this->assertSame($container, $fixture->container);
    }
}
