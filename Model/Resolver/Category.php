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
use Mageplaza\Faqs\Api\FaqsRepositoryInterface;

/**
 * Class Category
 * @package Mageplaza\FaqsGraphQl\Model\Resolver
 */
class Category implements ResolverInterface
{
    /**
     * @var FaqsRepositoryInterface
     */
    private $faqsRepository;
    /**
     * @var Data
     */
    private $helperData;

    /**
     * Categories constructor.
     *
     * @param FaqsRepositoryInterface $faqsRepository
     * @param Data $helperData
     */
    public function __construct(
        FaqsRepositoryInterface $faqsRepository,
        Data $helperData
    ) {
        $this->faqsRepository        = $faqsRepository;
        $this->helperData = $helperData;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $searchCriteria = $this->helperData->validateAndAddFilter($args, 'categories');
        $items        = $this->faqsRepository->getCategories($searchCriteria);
        $pageInfo = $this->helperData->getPageInfo($items, $searchCriteria, $args);

        return [
            'total_count' => count($items),
            'items'       => $items,
            'pageInfo'    => $pageInfo
        ];
    }
}
