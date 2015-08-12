Feature: Product
In order to get and update product information
As a Schilling Web Service user
I need to be able to perform queries to the Web Service

  Scenario: Performing a product query
    Given I have a random product number
    And then send a query for a product
    Then the responses product number should match the query
    And Product text should be a string

  Scenario: Getting list of product internet categories
    Given I send request for all product internet categories
    Then the response should have atleast 1 values

  Scenario: Getting a certain product internet category
    Given I have a random internet category id
    And then send a query for an internet category 
    Then the responses internet category id matches the query

  Scenario: Getting discount information for a product
    Given I have a random product number
    And then send a query for discount information
    Then the response should have atleast 1 values
