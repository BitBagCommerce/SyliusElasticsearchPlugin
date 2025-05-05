@searching_products
Feature: Filtering products
    In order to find a product I am into
    As a Customer
    I want to be able to filter products by specific criteria

    Background:
        Given the store operates on a single channel in "United States"
        And the store classifies its products as "Shirts"
        And there is a product named "Loose white designer T-Shirt" in the store
        And there is a product named "Everyday white basic T-Shirt" in the store
        And there is a product named "Ribbed copper slim fit Tee" in the store
        And there is a product named "Oversize white cotton T-Shirt" in the store
        And there is a product named "Raglan grey & black Tee" in the store
        And there is a product named "Sport basic white T-Shirt" in the store
        And these products belongs primarily to "Shirts" taxon
        And the data is populated to Elasticsearch

    @api
    Scenario: Filtering products by name
        When I search the products by "shirt" phrase
        Then I should see 4 products
