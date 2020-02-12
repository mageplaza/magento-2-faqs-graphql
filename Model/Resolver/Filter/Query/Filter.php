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

namespace Mageplaza\FaqsGraphQl\Model\Resolver\Filter\Query;

use Magento\Framework\Api\SearchCriteriaInterface;
use Mageplaza\Faqs\Model\Category as CategoryModel;
use Mageplaza\Faqs\Model\Article as ArticleModel;
use Mageplaza\FaqsGraphQl\Model\Resolver\Filter\DataProvider\Category;
use Mageplaza\FaqsGraphQl\Model\Resolver\Filter\DataProvider\Article;
use Mageplaza\FaqsGraphQl\Model\Resolver\Filter\SearchResult;
use Mageplaza\FaqsGraphQl\Model\Resolver\Filter\SearchResultFactory;

/**
 * Retrieve filtered product data based off given search criteria in a format that GraphQL can interpret.
 */
class Filter
{
    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var Article
     */
    private $postDataProvider;

    /**
     * @var Category
     */
    private $categoryDataProvider;

    /**
     * Filter constructor.
     *
     * @param SearchResultFactory $searchResultFactory
     * @param Article $articleDataProvider
     * @param Category $categoryDataProvider
     */
    public function __construct(
        SearchResultFactory $searchResultFactory,
        Article $articleDataProvider,
        Category $categoryDataProvider
    ) {
        $this->searchResultFactory  = $searchResultFactory;
        $this->postDataProvider     = $articleDataProvider;
        $this->categoryDataProvider = $categoryDataProvider;
    }

    /**
     * Filter catalog product data based off given search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param string $type
     *
     * @param null $collection
     *
     * @return SearchResult
     */
    public function getResult(
        SearchCriteriaInterface $searchCriteria,
        $type = 'article',
        $collection = null
    ): SearchResult {
        switch ($type) {
            case 'category':
                $list = $this->categoryDataProvider->getList($searchCriteria, $collection);
                break;
            case 'article':
            default:
                $list = $this->postDataProvider->getList($searchCriteria, $collection);
                break;
        }

        $listArray = [];
        /** @var ArticleModel|CategoryModel $post */
        foreach ($list->getItems() as $item) {
            $listArray[$item->getId()]          = $item->getData();
            $listArray[$item->getId()]['model'] = $item;
        }

        return $this->searchResultFactory->create($list->getTotalCount(), $listArray);
    }
}
