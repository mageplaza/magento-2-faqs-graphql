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

namespace Mageplaza\FaqsGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\Filter\Query\Filter;

/**
 * Class Article
 * @package Mageplaza\FaqsGraphQl\Model\Resolver
 */
class Article implements ResolverInterface
{

    /**
     * @var Data
     */
    private $_helperData;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * Categories constructor.
     *
     * @param Data $helperData
     * @param Filter $filter
     */
    public function __construct(
        Data $helperData,
        Filter $filter
    ) {
        $this->_helperData = $helperData;
        $this->filter      = $filter;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $searchCriteria = $this->_helperData->validateAndAddFilter($args, 'articles');
        $searchResult = $this->filter->getResult($searchCriteria, 'article', null);
        $items          = $this->_helperData->getApiSearchResult($searchResult);
        $pageInfo = $this->_helperData->getPageInfo($items, $searchCriteria, $args);

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items'       => $items,
            'pageInfo'    => $pageInfo
        ];
    }
}
