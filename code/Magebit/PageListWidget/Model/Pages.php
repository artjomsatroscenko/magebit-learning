<?php

namespace Magebit\PageListWidget\Model;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class Pages implements ArrayInterface
{
    protected CollectionFactory $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ){
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray(): array
    {
        $pages = [];
        $collection = $this->collectionFactory->create();
        foreach ($collection as $page) {
            $pages[] = [
                'value' => $page->getId(),
                'label' => $page->getTitle()
            ];
        }
        return $pages;
    }
}
