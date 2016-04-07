Feature: I can make API requests

  Scenario: Fetch a user
    When I get a user
    Then The response validates

  Scenario: Fetch a user by id
    When I get a user by id
    Then The response validates

  Scenario: Fetch a user by name
    When I get a user by name
    Then The response validates

  Scenario: Fetch a user by name and age
    When I get a user by name and age
    Then The response validates

  Scenario: Fetch a user by name and limit 1
    When I get a user by name and limit 1
    Then The response validates

  Scenario: Create a user (array, json)
    When I create a user from "array" as "json"
    Then The response validates

  Scenario: Create a user (array, form)
    When I create a user from "array" as "formurlencoded"
    Then The response validates

  Scenario: Create a user (object, json)
    When I create a user from "object" as "json"
    Then The response validates

  Scenario: Create a user (object, form)
    When I create a user from "object" as "formurlencoded"
    Then The response validates

  Scenario: Create a user (json_serializable, json)
    When I create a user from "json_serializable" as "json"
    Then The response validates

  Scenario: Create a user (json_serializable, form)
    When I create a user from "json_serializable" as "formurlencoded"
    Then The response validates

  Scenario: Create a user (string, json)
    When I create a user from "string" as "json"
    Then The response validates

  Scenario: Create a user (string, form)
    When I create a user from "string" as "formurlencoded"
    Then The response validates

  Scenario: Create a user (parts, json)
    When I create a user from "parts" as "json"
    Then The response validates

  Scenario: Create a user (parts, form)
    When I create a user from "parts" as "formurlencoded"
    Then The response validates

  Scenario: Upload avatar (string, array)
    When I upload an avatar as "string" from "array"
    Then The response validates

  Scenario: Upload avatar (resource, array)
    When I upload an avatar as "resource" from "array"
    Then The response validates

  Scenario: Upload avatar (string, object)
    When I upload an avatar as "string" from "object"
    Then The response validates

  Scenario: Upload avatar (string, json_serializable)
    When I upload an avatar as "string" from "json_serializable"
    Then The response validates

  Scenario: Upload avatar (resource, json_serializable)
    When I upload an avatar as "resource" from "json_serializable"
    Then The response validates

  Scenario: Upload avatar (string, parts)
    When I upload an avatar as "string" from "parts"
    Then The response validates

  Scenario: Upload avatar (resource, parts)
    When I upload an avatar as "resource" from "parts"
    Then The response validates

  Scenario: Upload avatar (string, string)
    When I upload an avatar as "string" from "string"
    Then The response validates

  Scenario: Upload avatar (resource, string)
    When I upload an avatar as "resource" from "string"
    Then The response validates

  Scenario: Get user with french language
    When I get a user with french language
    Then The response validates

  Scenario: Get a user returning mock api response
    When I get a user and receive a mock api response
    Then The response validates

  Scenario: Get a user returning retrofit response
    When I get a user and receive a retrofit response
    Then The response validates

  Scenario: Get a user returning retrofit response
    When I get a user and receive an array response
    Then The response validates

  Scenario: Get a user returning retrofit response
    When I get a user and receive a raw response
    Then The response validates
