# RESUME BUILDING WEB APPLICATION
A set of PHP scripts that automatically build and present an interactive resume.


# What it does

---
* The entire site is a dynamically loading set of scripts that makes calls to the database to query for information and present it in an interactive website
* The horizontat top menu builds your company history from newest to oldest (Left to right) clicking on these menu objects loads your job history in the page below it
* The company specific pages show your job history at that company and are listed by most recent positions first vertically 
* The company logo is displayed to the left of your job history and is a link to a modal that presents the company description information
* The right-aligned login button allows one to login and authenticates with account defined in the user-accounts in the database and creates an authenticated session 
* If the successfully logged in user has an Admin accesslevel, then a new Add/Update button is displayed that allows the following:

### 1. **Company information** 
**Add/Update companies**. This includes the Company name, the Company logo and an html formatted description of the Company itself

### 2. **Job History information** 
**Add/Update jobs.** This includes the title of the position they held, the start and end dates, and an html formatted description of the role. 
If they held more than one role, additional entries can be made associated with that same company and the resulting company page would show all roles chronologically from most recent to oldest role 

### 3. **Skill Categories** 
**Add/Update Categories.** This allows grouping of soft and hard skills by category for use on the home page of the generated site 

### 4. **Skills** 
**Add/Update Skills.** This includes indicating the category the skill should be associated with.

### 5. **Account Management** 
**Add/Update Users.** This includes User name , Password, and Access Level.
### 6. **Profile**
**Update Profile Information** The initial base.php page and the top menu both get information from the profile table in the database. These values can be updated in the admin console modal. 
 
## Unauthenticated Access 
 
![Unauthenticated view ](images/unauthenticated_login.png)

## Authenticated login with Administrator level credentials

![Authenticated view ](images/authenticated_login.png)

## Administrator Modal 
![Admin Modal](images/admin-modal.png)

## Edit/Update Profile via the Administrator modal
![Update Profile in Modal](images/profile-admin-modal.png)


---



## How to use:

- [Installation](#installation)
- [Technologies](#technologies)
- [Dependencies](#dependencies)
- [Team](#team)

---

## Installation
### Clone

- Clone this repository to your local machine using the command below:
```
	$ git clone git@github.com:eldday/webresume.git
```

### Configuration

> Access project root


``` cd /sourcedir  ```
> Linux Server running Debian
``` ``` 
> update the db_connection script to match your database installation
``` ```

> Seed the database 
``` mysql create database webresume ```
``` mysql -u [user name] -p [targer db name] < [dumpfile.sql] ```
```INSERT into USER_ACCOUNTS (username, password, accessLevel) VALUES ($username,$password.$accessLevel);```

> at least one admin account defined in the User_accounts table with admin access


> run the hasher script to encrpt the intitial admin password in the database


---

---
## Technologies:
- PHP
- Javascript
- HTML

### Dependencies
* *[PHP](https://www.php.net/downloads.php)*
* *[Apache Server](https://httpd.apache.org/)*


> ![](https://github.com/eldday/webresume/blob/main/images/DDAYLOGO.gif) Patrick Day  | <a href="https://www.linkedin.com/in/eldday/" target="_blank">**Linkedin**</a> | <a href="https://github.com/eldday" target="_blank">**Github**</a> 

---
