# Migration Practice

## About this development environment

This is a [Lando](https://docs.devwithlando.io) local dev environment.

### Requirements
Latest versions of [Lando >= 3.0.0-rc.16](https://docs.devwithlando.io)

### Setup the project
From the project root, run `lando start` and wait for the containers to build. Once the containers have started, follow these steps:
1- Import the D8 database d8-db.sql.gz from the root of this repository. This db includes the installed drupal 8 site and two content types, "Player" and "Team" and their fields.
```
lando db-import --user=drupal8 d8-db.sql.gz
```
2- Download and Import our source data from [here](http://seanlahman.com/files/database/2016-03-09_mysql-core.zip)

*We will be using the Teams and Master tables.

```
lando db-import --user=stats stats.sql
```

3- Run lando info and ,make sure you are getting the same result as follows:
```
{
    service: 'database',
    urls: [],
    type: 'mysql',
    internal_connection: {
      host: 'database',
      port: '3306'
    },
    external_connection: {
      host: 'localhost',
      port: '32815'
    },
    creds: {
      database: 'drupal8',
      password: 'drupal8',
      user: 'drupal8'
    },
    config: {
      database: '/Users/username/.lando/config/drupal8/mysql.cnf'
    },
    version: '5.7',
    meUser: 'www-data',
    hostnames: [
      'database.drupal8migration.internal'
    ]
```

### Practice migration steps
1- These modules should be enables:
- migrate
- migrate_plus
- migrate_tools
- migration (this is our custom migration module for this project, I should have named it better)

2- cd to web directory:
This command will give you all the migration tools command lines:
```
lando drush --filter=migrate
```
3- migrate:status
```
lando drush ms
```
* There should be two migration config baseball_player and baseball_team with status of Idle
* If migration module is not enabled use lando drush dre migration

* If you get error that stats user doesnt have access to the database, follow these steps:
    1- first try to rebuild the lando containers
        ```
        lando rebuild
        ```
    2- if still getting the error make sure that you have the stats user inside your lando db container.
    >> SQLSTATE[HY000] [1045] Access denied for user 'stats'@'172.27.0.3' (using password: YES)
        ```
        lando mysql
        ```
        ```
        mysql> use mysql;
        ```
        ```
        mysql> select user, host from user;
        ```

    you should see

    |   user	|   host	|
    |---	|---	|
    |   drupal8	|   %	|
    |   root	|   %	|
    |   stats	|   localhost	|
    if not, you should add the stats user and
        ```
        GRANT ALL PRIVILEGES ON *.* TO 'stats'@'localhost';
        ```

4- Migrate Teams
```
lando mim baseball_team --limit=2
```
* if you change your migration config file, you should follow these steps:
1- lando drush mr baseball_team
2- lando drush ms ( check status of both migration config set to Idle)
3- lando drush cim -y
4- lando drush dre migration
5- lando drush ms
6- lando drush mim baseball_team --limit=3
