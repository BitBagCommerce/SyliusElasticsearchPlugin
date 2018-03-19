@managing_inventory
Feature: Filtering products
    In order to find a product I am into
    As a Customer
    I want to be able to filter products by specific criteria

    Background:
        Given the store operates on a channel named "Web-US" in "USD" currency
        And there are 15 t-shirts in the store
        And 5 of these products are priced between "$10" and "$15"
        And 10 of these products are priced between "$15" and "$35"
        And these "products" have "size" option
        And 3 of these products have "size" option with "S" value
        And 5 of these products have "size" option with "M" value
        And 7 of these products have "size" option with "M" value
        And these "products" have "color" attribute
        And 3 of these products have "color" attribute with "Red" value
        And 5 of these products have "color" attribute with "Green" value
        And 7 of these products have "color" attribute with "Blue" value

    @ui
    Scenario: Filtering products by name
        When I go to the shop products page
        And I search the products by "shirt" phase
        Then I should see 9 products on the first page
        And I should see 6 products on the second page

    @ui
    Scenario: Filtering products by options
        When I go to the shop products page
        And I filter products by "S" "Size" option
        And I filter products by "M" "Size" option
        Then I should see 8 products on the page

    @ui
    Scenario: Filtering products by attributes
        When I go to the shop products page
        And I filter products by "Red" "Color" attribute
        And I filter products by "Green" "Color" attribute
        Then I should see 8 products on the page

    @ui
    Scenario: Filtering products by price
        When I go to the shop products page
        And I filter product price between 10 and 15
        Then I should see 5 products on the page

    @ui
    Scenario: Changing limit from 9 to 18
        Given there are 40 more products in the store
        When I go to the products page
        And I change the limit to 18
        Then I should see 18 products on the first page
        And I should see 18 products on the second page
        And I should see 9 products on the third page

    @ui
    Scenario: Changing limit from 9 to 36
        Given there are 40 more products in the store
        When I go to the products page
        And I change the limit to 36
        Then I should see 36 products on the first page
        And I should see 9 products on the second page
