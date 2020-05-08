@listing_products
Feature: Listing products
  In order to browse shop products
  As a Visitor
  I want to list products by category

  Background:
    Given the store operates on a channel named "Web-US"
    And this channel allows to shop using the "Spanish (Spain)" locale
    And this channel uses the "English (United States)" locale as default
    And the store has taxonomy named "T-Shirts" in "English (United States)" locale and "T-Shirts" in "Spanish (Spain)" locale
    And there are 7 t-shirts in the store
    And there is a product named "United States" in the store
    And this product is not translated in the "Spanish (Spain)" locale
    And there is a product named "Germany" in the store
    And this product is not translated in the "Spanish (Spain)" locale
    And these products belongs to "T-Shirts" taxon
    And the data is populated to Elasticsearch

  @ui
  Scenario: Listing products in default locale
    When I go to the shop products page for "T-Shirts" taxon
    Then I should see 9 products on the page

  @ui
  Scenario: Listing products in another locale
    When I go to the shop products page for "T-Shirts" taxon in the "Spanish (Spain)" locale
    Then I should see 7 products on the page
