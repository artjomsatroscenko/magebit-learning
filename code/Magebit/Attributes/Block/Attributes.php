<?php

declare(strict_types=1);

namespace Magebit\Attributes\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\View\Description;

class Attributes extends Template
{
    public Description $description;

    public function __construct(
        Description      $description,
        Template\Context $context,
        array            $data = []
    ) {
        parent::__construct($context, $data);
        $this->description = $description;
    }

    /**
     * Returns 3 attributes for product, if some of attributes value or label is missing it will go to next attribute
     * @return array
     */
    public function getThreeAttributes(): array
    {
        $_product = $this->description->getProduct();
        $getAllAttributes = $_product->getAttributes();

        foreach ($getAllAttributes as $a) {
            $allAttr[] = $a->getName();
        }

        $attributes = ['dimension', 'color', 'material'];
        $otherAttributes = array_diff($allAttr, $attributes);
        $fullArray = $attributes + $otherAttributes;

        $count = 0;
        $i = 0;
        $threeAttribute = [];
        $productResource = $_product->getResource();
        while ($count < 3 && $i < count($fullArray)) {
            $myAttr = $productResource->getAttribute($fullArray[$i]);
            if (!empty($myAttr->getStoreLabel()) && !empty($myAttr->getFrontend()->getValue($_product))) {
                $threeAttribute[] = [
                    'value' => $myAttr->getStoreLabel(),
                    'label' => $myAttr->getFrontend()->getValue($_product)
                ];
                $count++;
            }
            $i++;
        }
        return $threeAttribute;
    }
}
