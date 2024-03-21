## Setup Issues

1. `config.php` File was not included in .gitignore, potential security vulnerability | fixed by including the file in a .gitignore
2. default password for database can be found in plain text inside the `docker-compose.yml` file | fixed by outsourcing to external .env file
3. the initial sql script `m183_lb2.sql` that should be executed at the db startup never selects the database `m183_lb2` | fixed by adding a use statement
4. more application credentials can be found in the `docker-compose.yml` file | fixed by outsourcing the configuration to a .env file

## Not fixed yet:
1. Login is a "GET" request... 
2. Login sets plain text cookies that can be modified

## To check
1. Database rights
2. Error messages and exceptions

## Testing Keywords
- DAST
- SAST