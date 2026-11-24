Feature: Define application's metadata (name, owner, ...)
	As a GLPI user,
	I need first to create, modify, duplicate or delete the application's metadata

Background:
#		Given I'm logged as user "test" with password "aut0test"
        Given I am on "/"
        When I fill in "Login" with "test"
        When I fill in "Password" with "aut0test"
        When I press "Post"
        Then I should be on "/front/central.php"

#		Given I'm authorized to fully use Apps
@javascript
	Scenario: Checking access to the menu
        When I hover over the menu "Assets"
        Then I should see the menu "Apps structures"
        
	Scenario: Checking access to the Add button
        Given I am on "/plugins/archisw/front/swcomponent.php"
        Then I should see the link "Add"

@javascript
	Scenario: Creating a new application
		Given I am on "/plugins/archisw/front/swcomponent.php"
		When I follow "Add"
		Then I am on "/plugins/archisw/front/swcomponent.form.php"
		And I should see the field "name"
		And I should see the field "version"
		And I should see the field "startyear"
		And I should see the field "level"
		And I should see the field "shortname"
		And I should see the field "description"
		And I should see the field "comment"
		And I should see the field "plugin_archisw_swcomponentstates_id"
		And I should see the field "_statedate"
		And I should see the field "plugin_archisw_swcomponenttypes_id"
		And I should see the field "plugin_archisw_swcomponenttechnics_id"
		And I should see the field "plugin_archisw_swcomponentinstances_id"
		And I should see the field "plugin_archisw_swcomponentdbs_id"
		And I should see the field "plugin_archisw_swcomponenttargets_id"
		And I should see the field "plugin_archisw_swcomponentusers_id"
		And I should see the field "plugin_archisw_swcomponentlicenses_id"
		And I should see the field "groups_id"
		And I should see the field "suppliers_id"
		And I should see the field "users_id"
		And I should see the field "manufacturers_id"
		And I should see the field "address"
		And I should see the field "plugin_archisw_swcomponentslas_id"
		And I should see the field "locations_id"
        And I should see the button "Add"
		And I fill in "name" with "Test Behat-Mink"
		And I fill in "version" with "Test"
		And I fill in "startyear" with "2018"
		And I fill in "shortname" with "Test"
		And I fill in "description" with "Test Behat-Mink : description"
		And I fill in "comment" with "Test Behat-Mink : comment"
		And I select option "In Use" from combobox "plugin_archisw_swcomponentstates_id"
		And I fill in "_statedate" with "2018-01-13"
		And I select option "Business Package" from combobox "plugin_archisw_swcomponenttypes_id"
		And I select option "Java" from combobox "plugin_archisw_swcomponenttechnics_id"
		And I select option "Prod" from combobox "plugin_archisw_swcomponentinstances_id"
		And I select option "Mysql" from combobox "plugin_archisw_swcomponentdbs_id"
		And I select option "Headquarters only" from combobox "plugin_archisw_swcomponenttargets_id"
		And I select option "> 500 users" from combobox "plugin_archisw_swcomponentusers_id"
		And I select option "OpenSource" from combobox "plugin_archisw_swcomponentlicenses_id"
		And I select option "test" from combobox "groups_id"
		And I select option "SAP Belgium" from combobox "suppliers_id"
		And I select option "test" from combobox "users_id"
		And I select option 3 from combobox "manufacturers_id"
		And I press "Add"
		And I should see a message "Item successfully added:"

@javascript
	Scenario: Modifying an existing application
		Given I am on "/plugins/archisw/front/swcomponent.php"
		When I search on "Name" from combobox "criteria[0][field]"
		And I fill in "criteria[0][value]" with "Test Behat-Mink"
		And I press "Search"
		And I follow "Test Behat-Mink"
		Then I should see the field "name"
		And I fill in "name" with "Test Behat-Mink modified"
		And I press "Save"
		And I should see a message "Item successfully updated:"
		Then I follow "Apps Structures"
		And I wait 1 s
		And I fill in "name" on this tab with "Test Behat-Mink : child"
		And I press "Add"
		And I should see a message "Item successfully added:"

@javascript
	Scenario: Duplicating an existing application
		Given I am on "/plugins/archisw/front/swcomponent.php"
		When I search on "Name" from combobox "criteria[0][field]"
		And I fill in "criteria[0][value]" with "Test Behat-Mink modified"
		And I press "Search"
		Then I check search result 1
		And I select massive action "Duplicate"
		And I press "Post"
		And I should see a message "Operation successful"
		
@javascript
	Scenario: Deleting an existing application
		Given I am on "/plugins/archisw/front/swcomponent.php"
		When I search on "Name" from combobox "criteria[0][field]"
		And I fill in "criteria[0][value]" with "Test Behat-Mink"
		Then I press "Search"
		And I check search result 1
		And I check search result 2
		And I check search result 3
		And I select massive action "Put in dustbin"
		And I press "Post"
		And I should see a message "Operation successful"
		Then I click on ".lever"
		And I check search result 1
		And I uncheck search result 2
		And I uncheck search result 3
		And I select massive action "Delete permanently"
		And I press "Post"
		And I should see a message "Operation successful"
		And I check search result 1
		And I check search result 2
		And I select massive action "Delete permanently"
		And I press "Post"
		And I should see a message "Operation successful"

