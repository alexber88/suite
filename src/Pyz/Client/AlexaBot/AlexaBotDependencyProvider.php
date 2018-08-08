<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AlexaBot;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class AlexaBotDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_CATALOG = 'CLIENT_CATALOG';
    const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    const CLIENT_CART = 'CLIENT_CART';
    const CLIENT_CHECKOUT = 'CLIENT_CHECKOUT';
    const CLIENT_CALCULATION = 'CLIENT_CALCULATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addCatalogClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addCheckoutClient($container);
        $container = $this->addCalculationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogClient(Container $container)
    {
        $container[self::CLIENT_CATALOG] = function (Container $container) {
            return $container->getLocator()->catalog()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductStorageClient(Container $container)
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return $container->getLocator()->productStorage()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCartClient(Container $container)
    {
        $container[self::CLIENT_CART] = function (Container $container) {
            return $container->getLocator()->cart()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCheckoutClient(Container $container)
    {
        $container[self::CLIENT_CHECKOUT] = function (Container $container) {
            return $container->getLocator()->checkout()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCalculationClient(Container $container)
    {
        $container[self::CLIENT_CALCULATION] = function (Container $container) {
            return $container->getLocator()->calculation()->client();
        };

        return $container;
    }
}