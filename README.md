# RESUME BUILDING WEB APPLICATION
A set of PHP scripts that automatically build and present an interactive resume that has the following capabilities:
## 1. **Company information** 
**Add/Update companies**. This includes the Company name, the Company logo and an html formatted description of the Company itself
## 2. **Job History information** 
**Add/Update jobs.** This includes the title of the position they held, the start and end dates, and an html formatted description of the role. 
If they held more than one role, additional entries can be made associated with that same company and the resulting company page would show all roles chronologically from most recent to oldest role 
## 3. **Skill Categories** 
**Add/Update Categories.** This allows grouping of soft and hard skills by category for use on the home page of the generated site 
## 4. **Skills** 
**Add/Update Skills.** This includes indicating the category the skill should be associated with.
## 5. **Account Management** 
**Add/Update Users.** This includes User name , Password, and Access Level.





## How to use:

- [Installation](#installation)
- [Examples](#examples)
- [Technologies](#technologies)
- [Patterns](#patterns)
- [Dependencies](#dependencies)
- [Reports](#reports)
- [Contributing](#contributing)
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

> Linux Server running Debian

> update the db_connection script to match your datbabase installation

> running mysql or maria database

> at least one admin account defined in the User_accounts table with admin access

> run the hasher script to encrpt passwords in the database
```
	$ cd /webresume
```
> Seed the Database
---

---
## Technologies:
- PHP
- Javascript
- HTML

### Dependencies
* *[PHP](https://www.php.net/downloads.php)*
* *[Apache Server](https://httpd.apache.org/)*

---
## 6. **What it does** 

The server will present and empty framework including:
* Black horizontal (top) Navigation bar with a single button ::LOGIN::
* An empty white main page with nothing shown




---


> ![](https://github.com/eldday/webresume/blob/main/images/DDAYLOGO.gif) Patrick Day  | <a href="https://www.linkedin.com/in/eldday/" target="_blank">**Linkedin**</a> | <a href="https://github.com/eldday" target="_blank">**Github**</a> 

---
