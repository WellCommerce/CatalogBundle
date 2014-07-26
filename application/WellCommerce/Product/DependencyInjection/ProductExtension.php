<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace WellCommerce\Product\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use WellCommerce\Core\DependencyInjection\AbstractExtension;

/**
 * Class ProductExtension
 *
 * @package WellCommerce\Product\DependencyInjection
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('layout.xml');
    }

    /**
     * {@inheritdoc}
     */
    public function registerRoutes(RouteCollection $collection, ContainerBuilder $container)
    {
        $adminCollection = new RouteCollection();

        $adminCollection->add('admin.product.index', new Route('/index', array(
            '_controller' => 'product.admin.controller:indexAction',
        )));

        $adminCollection->add('admin.product.add', new Route('/add', array(
            '_controller' => 'product.admin.controller:addAction',
        )));

        $adminCollection->add('admin.product.edit', new Route('/edit/{id}', array(
            '_controller' => 'product.admin.controller:editAction',
            'id'          => null
        )));

        $adminCollection->addPrefix('/admin/product');

        $collection->addCollection($adminCollection);

        // front collection
        $frontCollection = new RouteCollection();

        $frontCollection->add('front.product.index', new Route('/{slug}', array(
            '_controller' => 'product.front.controller:indexAction',
            'slug'        => null
        )));

        $frontCollection->addPrefix('/product');

        $collection->addCollection($frontCollection);
    }
}