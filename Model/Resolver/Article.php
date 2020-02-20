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
use Mageplaza\Faqs\Api\FaqsRepositoryInterface;
use Mageplaza\Faqs\Helper\Data;

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
     * @var FaqsRepositoryInterface
     */
    private $faqsRepository;

    /**
     * Categories constructor.
     *
     * @param Data $helperData
     * @param FaqsRepositoryInterface $faqsRepository
     */
    public function __construct(
        Data $helperData,
        FaqsRepositoryInterface $faqsRepository
    ) {
        $this->_helperData           = $helperData;
        $this->faqsRepository        = $faqsRepository;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $searchCriteria = $this->_helperData->validateAndAddFilter($args, 'articles');
        $items        = $this->faqsRepository->getCategories($searchCriteria);
        $pageInfo = $this->_helperData->getPageInfo($items, $searchCriteria, $args);

        return [
            'total_count' => count($items),
            'items'       => $items,
            'pageInfo'    => $pageInfo
        ];
    }
}
