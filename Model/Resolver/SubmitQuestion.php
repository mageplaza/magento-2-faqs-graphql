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

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Faqs\Api\FaqsRepositoryInterface;
use Mageplaza\Faqs\Helper\Data;
use Mageplaza\Faqs\Model\Article as ArticleModel;
use Mageplaza\Faqs\Model\ArticleFactory;
use Mageplaza\Faqs\Model\Filter\Query\Filter;

/**
 * Class SubmitQuestion
 * @package Mageplaza\FaqsGraphQl\Model\Resolver
 */
class SubmitQuestion implements ResolverInterface
{
    /**
     * @var string
     */
    protected $_aclResource = '';

    /**
     * @var string
     */
    protected $_type = '';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ArticleFactory
     */
    protected $_articleFactory;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var FaqsRepositoryInterface
     */
    private $faqsRepository;

    /**
     * AbstractResolver constructor.
     *
     * @param Filter $filter
     * @param DateTime $dateTime
     * @param StoreManagerInterface $storeManager
     * @param ArticleFactory $articleFactory
     * @param ProductRepository $productRepository
     * @param FaqsRepositoryInterface $faqsRepository
     * @param Data $helper
     */
    public function __construct(
        Filter $filter,
        DateTime $dateTime,
        StoreManagerInterface $storeManager,
        ArticleFactory $articleFactory,
        ProductRepository $productRepository,
        FaqsRepositoryInterface $faqsRepository,
        Data $helper
    ) {
        $this->filter            = $filter;
        $this->helper            = $helper;
        $this->_date             = $dateTime;
        $this->_storeManager     = $storeManager;
        $this->_articleFactory   = $articleFactory;
        $this->productRepository = $productRepository;
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
        $params = $args['input'];
        $this->validateQuestion($params);

        $articleData = [
            'name'         => strip_tags($params['content']),
            'author_name'  => strip_tags((isset($params['name']) ? $params['name'] : 'Guest')),
            'author_email' => isset($params['email']) ? $params['email'] : 'Guest@gmail.com',
            'position'     => 0,
            'store_ids'    => 0,
            'email_notify' => isset($params['is_notify']) ? $params['is_notify'] : 0,
            'meta_robots'  => 0,
        ];
        /** @var ArticleModel $articleModel */
        $articleModel = $this->_articleFactory->create();
        if (!empty($params['product_id'])) {
            $articleData ['product_id'] = (int) $params['product_id'];
        }

        $articleModel->setData($articleData);

        $this->faqsRepository->createQuestion($articleModel);

        return [
            'notify' => __('Question is added successfully'),
            'status' => true
        ];
    }

    /**
     * @param $data
     *
     * @throws GraphQlInputException
     * @throws NoSuchEntityException
     */
    public function validateQuestion(&$data)
    {
        if (!isset($data['content']) || empty($data['content'])) {
            throw new GraphQlInputException(__('Content question value must be not empty.'));
        }

        if (isset($data['is_notify']) && !in_array($data['is_notify'], ['1', '0'], true)) {
            $data['is_notify'] = '0';
        }

        if (isset($data['product_id']) && !is_numeric($data['product_id'])) {
            throw new GraphQlInputException(__('Product Id value must be integer.'));
        }

        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new GraphQlInputException(__('Email has a wrong format'));
        }

        if (isset($data['product_id']) && !$this->productRepository->getById($data['product_id'])->getId()) {
            throw new GraphQlInputException(__('Does not exist product with Id is %1', $data['product_id']));
        }
    }
}
