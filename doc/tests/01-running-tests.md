# Running Tests #

## Configure Database ##

If your modules use database, prior to testing make sure that database is set up in `.env` file by running the following command in project directory:

    php run installer

Run this command only once.

## Prepare Test Configuration File ##

Run the following command to collect tests from all [Dubysa packages](#) into `phpunit.xml` configuration file in project directory:

    php run config:phpunit

Re-run this command if you define new test files.

## Run Tests In Command Line ##

Run the following command in project directory

    vendor/bin/phpunit

Alternatively, [run tests in PHPStorm](#).

## Use Optimization Flags ##

By default, before running tests:

* cache is cleared
* migrations are rerun in test database from scratch
* webpack is run to prepare assets for `testing` environment

These operation make whole test suite considerably slower. You may configure test suite not to run these operations by running the following command in project directory:

    php run config:phpunit -fmw

Here `f` flag disables running `php fresh`, `m` flag disables running migrations, `w` flag disables running Webpack. You may also use only some of these flags.

Faster tests, however, requires additional care:

1. While running tests, also run the following command in separate console:

        npm run testing-watch

    Restart this command whenever [Webpack list of entry point directories and files](#) changes.

2. Tests which run on database are (and should be) written so that database after running tests is left unmodified. However, in some cases, errors in such tests may result in changed database. If database changes, restore it to "factory state" by running this command:

        php run migrations --env=testing --fresh

## Include Only Certain Test Groups ##

By default, all tests are included into configuration file. 

You can include only certain test suites by running the following command in project directory:

    php run config:phpunit [test_group1] [test_group2] ... [test_groupN]

Test suites (in order of execution):

* `unit_tests` test public interfaces of various classes which *don't touch database*. Usually these tests are very fast to execute.
* `db_tests` test database operations such as creating a table or running a SELECT statement.
* `app_tests` test application logic which *does use database*. Some application tests run PHP classes directly, others render full pages and test what is on them.  
* `doc_tests` test whether documentation is up to date.

List of test suites in your project may be different as modules may introduce new custom test suites. You can see full configuration of test suites by running the following command in project directory:

    php run show:config test_suites

Of course, you can include test suites and use optimization flags in one command:

    php run config:phpunit -fmw unit_tests db_tests

## Exclude Directories ##

By default, `tests` directories of all Dubysa packages are scanned for tests. You may add exclusion patterns to your [`.componentignore` file](#) to skip certain tests.

Example line below excludes all tests in specified package:

    vendor/dubysa/components/tests/*

