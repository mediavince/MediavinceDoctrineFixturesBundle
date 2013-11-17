<?php

namespace Mediavince\DoctrineFixturesBundle\Common\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Loader as BaseLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class Loader extends BaseLoader
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addFixture(FixtureInterface $fixture)
    {
        if ($fixture instanceof ContainerAwareInterface) {
            $fixture->setContainer($this->container);
        }

        parent::addFixture($fixture);
    }

    /**
     * Find fixtures classes in a given directory and load them.
     *
     * @param string $dir Directory to find fixture classes in.
     * @return array $fixtures Array of loaded fixture object instances
     */
    public function loadFromDirectory($dir)
    {
        $fixtures = array();
        $includedFiles = array();

        if (is_file($dir))
        {
            // return $this->loadFromFile($dir);
            $sourceFile = realpath($dir);
            require_once $sourceFile;
            $includedFiles[] = $sourceFile;
        }
        else {
            if (!is_dir($dir)) {
                throw new \InvalidArgumentException(sprintf('"%s" does not exist', $dir));
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($iterator as $file) {
                if (($fileName = $file->getBasename($this->fileExtension)) == $file->getBasename()) {
                    continue;
                }
                $sourceFile = realpath($file->getPathName());
                require_once $sourceFile;
                $includedFiles[] = $sourceFile;
            }
        }

        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $reflClass = new \ReflectionClass($className);
            $sourceFile = $reflClass->getFileName();

            if (in_array($sourceFile, $includedFiles) && ! $this->isTransient($className)) {
                $fixture = new $className;
                $fixtures[] = $fixture;
                $this->addFixture($fixture);
            }
        }
        return $fixtures;
    }

    /**
     * Find fixtures classes in a given file and load them.
     *
     * @param string $file File with fixture classe in.
     * @return array $fixtures Array of loaded fixture object instances
     */
    public function loadFromFile($file)
    {
        if (!is_file($file))
        {
            throw new \InvalidArgumentException(sprintf('"%s" does not exist', $file));
        }

        $fixtures = array();
        $includedFiles = array();

        $sourceFile = realpath($file);
        require_once $sourceFile;
        $includedFiles[] = $sourceFile;

        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $reflClass = new \ReflectionClass($className);
            $sourceFile = $reflClass->getFileName();

            if (in_array($sourceFile, $includedFiles) && ! $this->isTransient($className)) {
                $fixture = new $className;
                $fixtures[] = $fixture;
                $this->addFixture($fixture);
            }
        }
        return $fixtures;
    }

}
