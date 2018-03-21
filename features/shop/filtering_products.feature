@managing_inventory
Feature: Filtering products
    In order to find a product I am into
    As a Customer
    I want to be able to filter products by specific criteria

    Background:
        Given the store operates on a channel named "Web-US" in "USD" currency
        And the store classifies its products as "T-Shirts"
        And there are 45 t-shirts in the store
        And these products belongs to "T-Shirts" taxon
        And 5 of these products are priced between "$10" and "$15"
        And 10 of these products are priced between "$15" and "$35"
        And these products have "Size" option with values "S,M,L"
        And 3 of these products have "Size" option with "S" value
        And 5 of these products have "Size" option with "M" value
        And 7 of these products have "Size" option with "L" value
        And these products have text attribute "color"
        And 3 of these products have text attribute "color" with "Red" value
        And 5 of these products have text attribute "color" with "Green" value
        And 7 of these products have text attribute "color" with "Blue" value
        And the data is populated to elasticsearch

    @ui
    Scenario: Filtering products by name
        When I go to the shop products page for "T-Shirts" taxon
        And I search the products by "shirt" phase
        Then I should see 9 products on 1 page
        And I should see 9 products on 2 page
        And I should see 9 products on 3 page
        And I should see 9 products on 4 page
        And I should see 9 products on 5 page

    @ui
    Scenario: Filtering products by options
        When I go to the shop products page for "T-Shirts" taxon
        And I filter products by "S" "Size" option
        And I filter products by "M" "Size" option
        Then I should see 8 products on the page

    @ui
    Scenario: Filtering products by attributes
        When I go to the shop products page for "T-Shirts" taxon
        And I filter products by "Red" "color" attribute
        And I filter products by "Green" "color" attribute
        Then I should see 8 products on the page

    @ui
    Scenario: Filtering products by price
        When I go to the shop products page for "T-Shirts" taxon
        And I filter product price between 10 and 15
        Then I should see 5 products on the page

    @ui
    Scenario: Changing limit from 9 to 18
        When I go to the shop products page for "T-Shirts" taxon
        And I change the limit to 18
        Then I should see 18 products on 1 page
        And I should see 18 products on 2 page
        And I should see 9 products on 3 page

    @ui
    Scenario: Changing limit from 9 to 36
        When I go to the shop products page for "T-Shirts" taxon
        And I change the limit to 36
        Then I should see 36 products on 1 page
        And I should see 9 products on 2 page
