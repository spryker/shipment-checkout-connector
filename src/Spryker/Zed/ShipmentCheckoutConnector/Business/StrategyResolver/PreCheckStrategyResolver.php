<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector\Business\StrategyResolver;

use Closure;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class PreCheckStrategyResolver implements PreCheckStrategyResolverInterface
{
    /**
     * @var string
     */
    public const STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT = 'STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT';

    /**
     * @var string
     */
    public const STRATEGY_KEY_WITH_MULTI_SHIPMENT = 'STRATEGY_KEY_WITH_MULTI_SHIPMENT';

    /**
     * @var array<\Closure>
     */
    protected $strategyContainer;

    /**
     * @param array<\Closure> $strategyContainer
     */
    public function __construct(array $strategyContainer)
    {
        $this->strategyContainer = $strategyContainer;
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentCheckoutConnector\Business\Shipment\ShipmentCheckoutPreCheckInterface
     */
    public function resolve(iterable $itemTransfers): ShipmentCheckoutPreCheckInterface
    {
        if (count($itemTransfers) === 0) {
            $this->assertRequiredStrategyWithoutMultiShipmentContainerItems();

            return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
        }

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipment() === null) {
                $this->assertRequiredStrategyWithoutMultiShipmentContainerItems();

                return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT]);
            }
        }

        $this->assertRequiredStrategyWithMultiShipmentContainerItems();

        return call_user_func($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT]);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithoutMultiShipmentContainerItems(): void
    {
        if (
            !isset($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT);
        }
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return void
     */
    protected function assertRequiredStrategyWithMultiShipmentContainerItems(): void
    {
        if (
            !isset($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT])
            || !($this->strategyContainer[static::STRATEGY_KEY_WITH_MULTI_SHIPMENT] instanceof Closure)
        ) {
            throw new ContainerKeyNotFoundException($this, static::STRATEGY_KEY_WITH_MULTI_SHIPMENT);
        }
    }
}
