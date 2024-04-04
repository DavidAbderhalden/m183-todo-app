FROM mariadb:latest
COPY docker-entrypoint-initdb.d/m183_lb2.sql /docker-entrypoint-initdb.d
EXPOSE 3306
CMD ["mariadbd"]
