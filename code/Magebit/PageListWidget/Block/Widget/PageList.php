<?php

declare(strict_types=1);

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Store\Model\StoreManagerInterface;

class PageList extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'page-list.phtml';

    private PageRepositoryInterface $pageRepositoryInterface;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private StoreManagerInterface $storeManager;

    public function __construct(
        Template\Context        $context,
        array                   $data = [],
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder   $searchCriteriaBuilder,
        StoreManagerInterface   $storeManager
    ){
        parent::__construct($context, $data);
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPages(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder;

        if ($pageMultiSelect = $this->getData('pagemultiselect')) {
            $pageIds = explode(",", $pageMultiSelect);
            $searchCriteria->addFilter('page_id', $pageIds, 'in');
        }

        $searchCriteria->addFilter('store_id', [0, $this->storeManager->getStore()->getId()], 'in');
        $searchCriteria = $searchCriteria->create();
        return $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
    }
}
