<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Money\Plugin\MoneyPlugin;
use Spryker\Client\Search\Dependency\Facade\SearchToLocaleClientBridge;
use Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface;
use Spryker\Client\Search\Exception\MissingSearchConfigPluginException;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Spryker\Client\Search\SearchConfig getConfig()
 */
class SearchDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const SEARCH_CONFIG_BUILDER = 'search config builder';

    /**
     * @var string
     */
    public const PLUGINS_CLIENT_ADAPTER = 'PLUGINS_CLIENT_ADAPTER';

    /**
     * @var string
     */
    public const SEARCH_CONFIG_EXPANDER_PLUGINS = 'search config expander plugins';

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var string
     */
    public const STORE = 'store';

    /**
     * @var string
     */
    public const PLUGIN_MONEY = 'money plugin';

    /**
     * @var string
     */
    public const PLUGINS_SEARCH_CONTEXT_EXPANDER = 'PLUGINS_SOURCE_IDENTIFIER_MAPPER';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        $container = $this->provideStore($container);
        $container = $this->addClientAdapterPlugins($container);

        $this->addSearchConfigBuilder($container);

        $container->set(static::SEARCH_CONFIG_EXPANDER_PLUGINS, function (Container $container) {
            return $this->createSearchConfigExpanderPlugins($container);
        });

        $container = $this->addMoneyPlugin($container);
        $container = $this->addSearchContextExpanderPlugins($container);
        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container->set(static::PLUGIN_MONEY, function () {
            return new MoneyPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @throws \Spryker\Client\Search\Exception\MissingSearchConfigPluginException
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigBuilderInterface
     */
    protected function createSearchConfigBuilderPlugin(Container $container)
    {
        throw new MissingSearchConfigPluginException(sprintf(
            'Missing instance of %s! You need to implement your own plugin and instantiate it in your own SearchDependencyProvider::createSearchConfigBuilder() to be able to search.',
            SearchConfigBuilderInterface::class,
        ));
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface>
     */
    protected function createSearchConfigExpanderPlugins(Container $container)
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function provideStore(Container $container)
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addClientAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CLIENT_ADAPTER, function () {
            return $this->getClientAdapterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface>
     */
    protected function getClientAdapterPlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchContextExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SEARCH_CONTEXT_EXPANDER, function () {
            return $this->getSearchContextExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface>
     */
    protected function getSearchContextExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchConfigBuilder(Container $container): Container
    {
        $container->set(static::SEARCH_CONFIG_BUILDER, function (Container $container) {
            return $this->createSearchConfigBuilderPlugin($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new SearchToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }
}
