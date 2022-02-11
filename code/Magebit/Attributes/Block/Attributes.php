<?php

declare(strict_types=1);

namespace Magebit\Attributes\Block;

use Magento\Catalog\Block\Product\View;

class Attributes extends View
{
    /**
     * Returns 3 attributes for product, if some of attributes value or label is missing it will go to next attribute
     * @return array
     */
    public function getThreeAttributes(): array
    {
        $product = $this->getProduct();
        $getAllAttributes = $product->getAttributes();

        if (empty($getAllAttributes)) {
            return [];
        }

        foreach ($getAllAttributes as $a) {
            $allAttr[] = $a->getName();
        }

        $attributes = ['dimension', 'color', 'material'];
        $otherAttributes = array_diff($allAttr, $attributes);
        $fullArray = $attributes + $otherAttributes;

        $count = 0;
        $i = 0;
        $threeAttribute = [];
        $productResource = $product->getResource();
        while ($count < 3 && $i < count($fullArray)) {
            $myAttr = $productResource->getAttribute($fullArray[$i]);
            $getLabel = $myAttr->getStoreLabel();
            $getValue = $myAttr->getFrontend()->getValue($product);
            if (!empty($getLabel) && !empty($getValue)) {
                $threeAttribute[] = [
                    'value' => $getLabel,
                    'label' => $getValue
                ];
                $count++;
            }
            $i++;
        }
        return $threeAttribute;
    }
}
