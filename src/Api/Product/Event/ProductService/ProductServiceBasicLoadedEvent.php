<?php declare(strict_types=1);

namespace Shopware\Api\Product\Event\ProductService;

use Shopware\Api\Configuration\Event\ConfigurationGroupOption\ConfigurationGroupOptionBasicLoadedEvent;
use Shopware\Api\Product\Collection\ProductServiceBasicCollection;
use Shopware\Api\Tax\Event\Tax\TaxBasicLoadedEvent;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;

class ProductServiceBasicLoadedEvent extends NestedEvent
{
    public const NAME = 'product_service.basic.loaded';

    /**
     * @var ShopContext
     */
    protected $context;

    /**
     * @var ProductServiceBasicCollection
     */
    protected $productServices;

    public function __construct(ProductServiceBasicCollection $productServices, ShopContext $context)
    {
        $this->context = $context;
        $this->productServices = $productServices;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): ShopContext
    {
        return $this->context;
    }

    public function getProductServices(): ProductServiceBasicCollection
    {
        return $this->productServices;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->productServices->getOptions()->count() > 0) {
            $events[] = new ConfigurationGroupOptionBasicLoadedEvent($this->productServices->getOptions(), $this->context);
        }
        if ($this->productServices->getTaxes()->count() > 0) {
            $events[] = new TaxBasicLoadedEvent($this->productServices->getTaxes(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}