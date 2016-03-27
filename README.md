## TKT \'ti-kə-ti\ (n)
A personal ticketing system with principles adapted from JIRA

I made this because I have a lot of personal project ideas floating around in my head and needed a tracking system; I saw JIRA and thought that would be good, but also a bit too robust for my relatively simple purposes, so I made one from scratch.

Please feel free to fork it, send me PRs if you think you have a nice improvement, etc.

How to start:
- Get a webserver running (I test on LAMP; Linux Apache MySQL PHP) that has MySQL and PHP
- Create a database (default "tkt")
- Import the tkt database (using inc/tkt.sql, command example "mysql -u root tkt < tkt.sql")
- Adjust credentials and database name in inc/db.php (default localhost, with user "root" and password "password")
- Set up your webserver with a php interpreter (needs php5-mysql[nd]) to point to the tkt folder you cloned/copied source to
- load your server on a browser
- begin playing around

Some notes:
- DB gets filled with some defaults, feel free to change by adjusting tkt.sql before importing, or afterwards via database manipulation
- Default administrator is "admin" password "admin", default view-only is "view" password "view" (you can see/change in tkt.sql)