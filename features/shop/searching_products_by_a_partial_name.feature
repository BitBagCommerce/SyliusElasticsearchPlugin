@searching_products
Feature: Filtering products
    In order to find a product I am into
    As a Customer
    I want to be able to filter products by specific criteria

    Background:
        Given the store operates on a channel named "Web-US" in "USD" currency
        And the store classifies its products as "Cars"
        And there is a product named "Volksvagen Polo" in the store
        And there is a product named "Volvo XC90" in the store
        And there is a product named "Polonez Caro" in the store
        And there is a product named "Porsche Carrera GT" in the store
        And these products belongs primarily to "Cars" taxon
        And the data is populated to Elasticsearch

    @api
    Scenario: Filtering products by name
        When I search the products by "vol" phrase
        Then I should see 2 products
