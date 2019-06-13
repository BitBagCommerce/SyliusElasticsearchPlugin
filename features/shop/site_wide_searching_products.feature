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
    And there is a product named "Volvo XC90" in the store
    And there is a product named "BMW 5 Series" in the store
    And there is a product named "Lamborghini Aventador" in the store
    And these products belongs primarily to "Cars" taxon
    And there is a product named "BMW GS" in the store
    And there is a product named "Ducati Monster" in the store
    And there is a product named "Honda Africa Twin" in the store
    And these products belongs primarily to "Motorbikes" taxon
    And the data is populated to Elasticsearch

  @ui
  Scenario: Searching products site wide
    When I search the products by "BMW" phrase in the site-wide search box
    Then I should see the following products in the search results page:
      | name          | taxon       |
      | BMW Z4        | Cars        |
      | BMW 5 Series  | Cars        |
      | BMW GS        | Motorbikes  |


