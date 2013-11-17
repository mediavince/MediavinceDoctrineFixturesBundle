DoctrineFixturesBundle
======================

This bundle integrates the [Doctrine2 Data Fixtures library](https://github.com/doctrine/data-fixtures).
into Symfony so that you can load data fixtures programmatically into the Doctrine ORM or ODM.

Documentation on how to install and use this bundle is available in the
Symfony2 [documentation](http://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html).

Media Vince added the possibility to pass a file as --fixtures parameter.
Also fixed a bug in the test suite, the load method of the ContainerAwareFixture needs to be passed an ObjectManager as parameter.
