<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_FaqsGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Mageplaza\FaqsGraphQl\Model\Resolver\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\FaqsGraphQl\Helper\Data;
use Mageplaza\Faqs\Model\Article;
use Mageplaza\Faqs\Model\Filter\Query\Filter;

/**
 * Class Product
 * @package Mageplaza\FaqsGraphQl\Model\Resolver\Resolver
 */
class Product implements ResolverInterface
{

    /**
     * @var Filter
     */
    protected $filterQuery;
    /**
     * @var Data
     */
    private $helperData;

    /**
     * Post constructor.
     *
     * @param Data $helperData
     * @param Filter $filterQuery
     */
    public function __construct(
        Data $helperData,
        Filter $filterQuery
    ) {
        $this->filterQuery = $filterQuery;
        $this->helperData  = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        /** @var Article $article */
        $article           = $value['model'];
        $productCollection = $article->getSelectedProductsCollection();
        $searchCriteria    = $this->helperData->validateAndAddFilter($args, 'faqs_product');
        $searchResult      = $this->filterQuery->getResult($searchCriteria, 'product', $productCollection);
        $items             = $this->helperData->getApiSearchResult($searchResult);
        $pageInfo          = $this->helperData->getPageInfo($items, $searchCriteria, $args);

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items'       => $items,
            'pageInfo'    => $pageInfo
        ];
    }
}
