@site_wide_searching_products
Feature: Site-wide products search
  In order to quickly find products I want to buy
  As a Customer
  I want to be able to search products across the entire catalog

  Background:
    Given the store operates on a channel named "Web-US" in "USD" currency
    And the store classifies its products as "Cars"
    And the store classifies its products as "Motorbikes"
    And there is a product named "BMW Z4" in the store
    And this product's price is "$42670"
    And this product belongs to "Cars"
    And there is a product named "Volvo XC90" in the store
    And this product's price is "$64505.80"
    And this product belongs to "Cars"
    And there is a product named "BMW 5 Series" in the store
    And this product's price is "$52070"
    And this product belongs to "Cars"
    And there is a product named "Lamborghini Aventador" in the store
    And this product's price is "$450000"
    And this product belongs to "Cars"
    And there is a product named "BMW GS" in the store
    And this product's price is "$18070"
    And this product belongs to "Motorbikes"
    And there is a product named "Ducati Monster" in the store
    And this product's price is "$14995"
    And this product's short description is:
      """
      This is the Ducati Monster which is much better than any other BMW motorbike.
      """
    And this product belongs to "Motorbikes"
    And there is a product named "Honda Africa Twin" in the store
    And this product's price is "$13490"
    And this product's description is:
      """
      This is the Honda Africa Twin which is like the BMW GS but from Honda.
      """
    And this product belongs to "Motorbikes"
    And the data is populated to Elasticsearch

  @ui
  Scenario: Searching products by name, description and short description in all taxons
    When I browse the search page
    And I search the products by "BMW" phrase in the site-wide search box
    Then I should see the product "BMW Z4" in the search results
    And I should see the product "BMW GS" in the search results
    And I should see the product "BMW 5 Series" in the search results
    And I should see the product "Honda Africa Twin" in the search results
    And I should see the product "Ducati Monster" in the search results

  @ui
  Scenario: Searching products from the home page
    When I open the home page
    And I search the products by "Lamborghini" phrase in the site-wide search box
    Then I should see the product "Lamborghini Aventador" in the search results

  @ui
  Scenario: Searching products and viewing price aggregations
    When I browse the search page
    And I search the products by "BMW" phrase in the site-wide search box
    Then I should see the following intervals in the price filter:
      """
      $10,000.00 - $20,000.00 (3)
      $40,000.00 - $50,000.00 (1)
      $50,000.00 - $60,000.00 (1)
      """
    And I should see 5 products in search results

  @ui
  Scenario: Searching products and viewing taxon aggregations
    When I browse the search page
    And I search the products by "BMW" phrase in the site-wide search box
    Then I should see the following options in the taxon filter:
      """
      Motorbikes (3)
      Cars (2)
      """
    And I should see 5 products in search results

  @ui
  Scenario: Searching products and filtering by price
    When I browse the search page
    And I search the products by "BMW" phrase in the site-wide search box
    And I filter by price interval "$10,000.00 - $20,000.00"
    And I filter by price interval "$50,000.00 - $60,000.00"
    Then I should see 4 products in search results

  @ui
  Scenario: Searching products and filtering by taxon
    When I browse the search page
    And I search the products by "BMW" phrase in the site-wide search box
    And I filter by taxon "Motorbikes"
    Then I should see 3 products in search results
