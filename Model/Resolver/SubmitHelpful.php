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

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\Faqs\Api\FaqsRepositoryInterface;
use Mageplaza\Faqs\Helper\Data;

/**
 * Class SubmitHelpful
 * @package Mageplaza\FaqsGraphQl\Model\Resolver
 */
class SubmitHelpful implements ResolverInterface
{

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var FaqsRepositoryInterface
     */
    private $faqsRepository;

    /**
     * SubmitHelpful constructor.
     *
     * @param FaqsRepositoryInterface $faqsRepository
     * @param Data $helper
     */
    public function __construct(
        FaqsRepositoryInterface $faqsRepository,
        Data $helper
    ) {
        $this->helper            = $helper;
        $this->faqsRepository    = $faqsRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helper->isEnabled()) {
            throw new GraphQlInputException(__('The module is disabled'));
        }

        return $this->faqsRepository->submitHelpful($args['input']['articleId'], $args['input']['isHelpful']);
    }
}
