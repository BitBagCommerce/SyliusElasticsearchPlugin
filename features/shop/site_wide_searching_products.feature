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
    And this product belongs to "Cars"
    And there is a product named "Volvo XC90" in the store
    And this product belongs to "Cars"
    And there is a product named "BMW 5 Series" in the store
    And this product belongs to "Cars"
    And there is a product named "Lamborghini Aventador" in the store
    And this product belongs to "Cars"
    And there is a product named "BMW GS" in the store
    And this product belongs to "Motorbikes"
    And there is a product named "Ducati Monster" in the store
    And this product's short description is:
      """
      This is the Ducati Monster which is much better than any other BMW motorbike.
      """
    And this product belongs to "Motorbikes"
    And there is a product named "Honda Africa Twin" in the store
    And this product's description is:
      """
      This is the Honda Africa Twin which is like the BMW GS but from Honda.
      """
    And this product belongs to "Motorbikes"
    And the data is populated to Elasticsearch

  @ui
  Scenario: Searching products by name, description and short description in all taxons
    When I search the products by "BMW" phrase in the site-wide search box
    Then I should see the product "BMW Z4" in the search results
    And I should see the product "BMW GS" in the search results
    And I should see the product "BMW 5 Series" in the search results
    And I should see the product "Honda Africa Twin" in the search results
    And I should see the product "Ducati Monster" in the search results

