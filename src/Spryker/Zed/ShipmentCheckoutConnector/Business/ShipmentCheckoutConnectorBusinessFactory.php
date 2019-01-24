<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentCheckoutPreCheck;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheck as ShipmentCheckoutPreCheckWithMultipleShippingAddress;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface;
use Spryker\Zed\ShipmentCheckoutConnector\Business\StrategyResolver\PreCheckStrategyResolver;
use Spryker\Zed\ShipmentCheckoutConnector\Business\StrategyResolver\PreCheckStrategyResolverInterface;
use Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig getConfig()
 */
class ShipmentCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Model\ShipmentCheckoutPreCheckInterface
     */
    public function createShipmentCheckoutPreCheck()
    {
        return new ShipmentCheckoutPreCheck($this->getShipmentFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface
     */
    public function createShipmentCheckoutPreCheckWithMultipleShippingAddress(): ShipmentCheckoutPreCheckInterface
    {
        return new ShipmentCheckoutPreCheckWithMultipleShippingAddress($this->getShipmentFacade());
    }

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeInterface
     */
    protected function getShipmentFacade()
    {
        return $this->getProvidedDependency(ShipmentCheckoutConnectorDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release. Use $this->createShipmentCheckoutPreCheckWithMultipleShippingAddress() instead.
     *
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\StrategyResolver\PreCheckStrategyResolverInterface
     */
    public function createShipmentCheckoutPreCheckStrategyResolver(): PreCheckStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer = $this->addShipmentCheckoutPreCheckWithoutMultipleShippingAddress($strategyContainer);
        $strategyContainer = $this->addShipmentCheckoutPreCheckWithMultipleShippingAddress($strategyContainer);

        return new PreCheckStrategyResolver($strategyContainer);
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentCheckoutPreCheckWithoutMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[PreCheckStrategyResolverInterface::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCheckoutPreCheck();
        };

        return $strategyContainer;
    }

    /**
     * @deprecated Will be removed in next major version after multiple shipment release.
     *
     * @param array $strategyContainer
     *
     * @return array
     */
    protected function addShipmentCheckoutPreCheckWithMultipleShippingAddress(array $strategyContainer): array
    {
        $strategyContainer[PreCheckStrategyResolverInterface::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createShipmentCheckoutPreCheckWithMultipleShippingAddress();
        };

        return $strategyContainer;
    }
}
