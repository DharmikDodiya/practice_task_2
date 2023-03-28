# features

# UnAuthenticated
* user login
* register with verification mail
* forget password

# Authenticated
* userProfile
* change Password
* update Profile
* get other user by id
* logout
* insert,update, delete , view , get module by id on module table
* insert,update, delete , view , get module by id on Permission table
* insert,update, delete , view , get module by id on Role table
* insert,update, delete , view , get module by id on ModulePermission table

# Models

* Role
* Permission
* Module
* ModulePermission
* PasswordReset
* User

# controllers

* AuthController
* UserController
* ModuleController
* ModulePermissionController
* Role Controller
* PermissionController

# create ApiListingTraits
* scearching record by name and description
* pagination page and perpage

# create helper function
* create method success and error

# create pivot Table
* Role_permissions
* user_roles

# create relationship 
* Many To Many

# Error Reporting
* Cretae Error model and migration
* create middleware DBTransaction.php

# create middleware
* UserMiddleware
* perform if user login check this particular user module permissions
* register in kernal.php



