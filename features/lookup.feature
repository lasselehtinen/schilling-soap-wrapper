Feature: Lookup
In order to get certain Lookup values
As a Schilling Web Service user
I need to be able to view the homepage

  Scenario: Performing a basic domain lookup
    Given I send a Lookup request for domain 26
    Then the response should have atleast 1 values

  Scenario: Performing a lookup on non-existing domain
    Given I send a Lookup request for domain 99999
    Then the request should throw an exception that contains message "Domain number not found"

  Scenario: Performing a domain lookup that requires an additional key
    Given I send a Lookup request for domain 308 with additional key 7
    Then the response should have atleast 1 values

  Scenario: Performing a domain lookup that requires an additional key without providing one
    Given I send a Lookup request for domain 308
    Then the request should throw an exception that contains message "Needed extra domain 7 not set using SetAdditionalKey"

