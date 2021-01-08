# Magento 2 FAQ GraphQL

**Magento 2 FAQ GraphQL is a part of Mageplaza FAQ extension that adds GraphQL features.** This supports PWA compatibility.

[Mageplaza FAQ for Magento 2](https://www.mageplaza.com/magento-2-faq-extension/) enables you to create a comprehensive FAQ page for your online store where visitors can find answers to questions about your products and services. 

You can build a neat and SEO-friendly FAQ homepage with the most convenience for customers to look up the information. The instant search box is placed in the center of the page, making it easier for customers to search for what they need. The FAQ page will be well-organized and user-friendly by classifying questions into specific categories. The number of questions included in a section is totally determined by the store admin. 

The store admin can configure the question FAQ homepage from the admin backend for SEO optimization. This helps increase the FAQ page search ranking and provides an easy-to-navigate the FAQ page easily. Each category can be customized to be more friendly with users by adding icons or changing the questions, positions of the category. 

The store admin can customize the look of the FAQ page from the backend configuration. It’s easy to change the page title, theme color, page layout, FAQ style, categories columns, and limit the question included in each category.  

## 1. How to install
Run the following command in Magento 2 root folder:

```
composer require mageplaza/module-faqs-graphql
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
**Note:** 
Magento 2 FAQ GraphQL requires installing [Mageplaza FAQ](https://www.mageplaza.com/magento-2-faq-extension/) in your Magento installation. 

## 2. How to use 

Mageplaza's FAQ extension enables getting categories, articles, and creating questions through GraphQL. 
To start working with FAQs GraphQL in Magento, you need to: 
- Use Magento 2.3.x. Return your site to developer mode. 
- Install [chrome extension](https://chrome.google.com/webstore/detail/chromeiql/fkkiamalmpiidkljmicmjfbieiclmeij?hl=en) (currently does not support other browsers). 

## 3. Devdocs
- [Magento 2 FAQ API & examples](https://documenter.getpostman.com/view/10589000/SzRxXqod?version=latest) 
- [Magento 2 FAQ GraphQL & examples](https://documenter.getpostman.com/view/10589000/SzRxXqof?version=latest)

Click on Run in Postman to add these collections to your workspace quickly. 

![Magento 2 blog graphql pwa](https://i.imgur.com/lhsXlUR.gif)

## 4. Contribute to this module 
Feel free to **Fork** and contribute to this module.

You can create a pull request, and we will consider to merge your changes in the main branch. 

## 5. Get support 
- Feel free to [contact us](https://www.mageplaza.com/contact.html) if you have any question. We're happy to hear from you. 
- If you find this post helpful, please give us a **Star** ![star](https://i.imgur.com/S8e0ctO.png)



