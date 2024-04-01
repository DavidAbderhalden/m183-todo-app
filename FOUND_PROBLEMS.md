## Setup Issues

1. `config.php` File was not included in .gitignore, potential security vulnerability | fixed by including the file in a .gitignore
2. default password for database can be found in plain text inside the `docker-compose.yml` file | fixed by outsourcing to external .env file
3. the initial sql script `m183_lb2.sql` that should be executed at the db startup never selects the database `m183_lb2` | fixed by adding a use statement
4. more application credentials can be found in the `docker-compose.yml` file | fixed by outsourcing the configuration to a .env file
5. Instead of using unsecure browser cookies to store user information I implemented session handling. Username and userid are now stored in a session.
6. The root user is not used anymore by the application, instead a dev user with local rights is being created and its minimum rights configurated inside the m183_lb2.sql file.
7. Exceptions where being displayed in a plain text for to the user on a production environment. A custom php.ini file is copied to the apache container now, which prevents that from happening
8. Login request has been changed from get to post.
9. Logout request can only be made when logged in now and is a post instead of get request.

## Not fixed yet:
4. Not hashed passwords in db
5. Fucking admin users created in init.sql file, should be random password etc. 

## To check
1. XSS attack possible bcs user input is not being serialized
2. SQL Injection is definitely possible
3. Create an .htaccess file to limit the risk of ssrf? (also can a file like config.php just be requested)?
4. Rate limit on login attempts?

## Testing Keywords
- DAST
- SAST
- CSRF
- SSRF
- SQL Injection
- Cross-Site-Scripting