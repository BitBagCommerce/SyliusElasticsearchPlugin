# UPGRADE FROM 3.* TO 4.0.0

#### Changes related to facets:

Since version 4 standard filters are deprecated, and replaced by facets. Facets are a more flexible and powerful way to filter products in the search engine. The main difference is that facets are not limited to the predefined values of the attribute, but they are generated based on the values of the products in the index. This means that the user can filter products by any value of the attribute, not only the predefined ones.

If you still want to use the old filters, you have to manually add them in form extension - they are still in the code: `BitBag\SyliusElasticsearchPlugin\Form\Type\ProductAttributesFilterType` and `BitBag\SyliusElasticsearchPlugin\Form\Type\ProductOptionsFilterType`.
