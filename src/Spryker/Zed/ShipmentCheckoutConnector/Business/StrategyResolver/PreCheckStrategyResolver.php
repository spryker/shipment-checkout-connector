<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\StrategyResolver;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface;
use Closure;

/**
 * @deprecated Remove strategy resolver after multiple shipment will be released.
 */
class PreCheckStrategyResolver implements PreCheckStrategyResolverInterface
{
    /**
     * @var array|Closure[]
     */
    protected $strategyContainer;

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @param array|Closure[] $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface
     */
    public function resolve(): ShipmentCheckoutPreCheckInterface
    {
        if (!defined(ItemTransfer::SHIPMENT)) {
            $this->assertRequiredStrategyWithoutMultiShipmentContainerItems();

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        $this->assertRequiredStrategyWithMultiShipmentContainerItems();

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function assertRequiredStrategyWithoutMultiShipmentContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function assertRequiredStrategyWithMultiShipmentContainerItems(): void
    {
        if (!isset($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}