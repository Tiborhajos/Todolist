###@todo###
====
A friendly todo list tool.

##Setup##
composer install

CREATE USER 'todo'@'localhost' IDENTIFIED BY 'test';
GRANT ALL ON todo_test.* TO 'todo'@'localhost';
FLUSH PRIVILEGES;

php app/console app:build-test-database

##To run##
app/console server:run
