## Backend API for Web App Development
Using pure PHP for the frontend web application using composer with Symfony HTTP Foundation.

### API Architecture
```
api.php             db.php                se.php
================    ================      ================
http responses      SQL database code     security
requests                                  sessions
json output                               restrictions
                                          data validation                          
```
### Disabled CORS
```
Windows CMD
"C:\Program Files\Google\Chrome\Application\chrome.exe" --disable-web-security --disable-gpu --user-data-dir=c:\tmp

```
### Project Setup
```
•	Clone github repo
•	Place the project in working directory www or htdocs
•	Open Localhost phpMyAdmin
•	Upload SQL file
•	Database Name ‘testtable’

```
### Database ERD
![Hotel Booking System ERD](https://user-images.githubusercontent.com/59464048/111923059-0290f700-8ae9-11eb-8cfc-add40819430a.png)
