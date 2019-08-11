# Creating The Database #

You can skip these steps if your application is not going to use database.

1. In shell, start MySql using `mysql -u root -p` command and enter these MySql statements:

		CREATE DATABASE {db_name};
	    GRANT ALL PRIVILEGES ON {db_name}.* TO '{db_user}' IDENTIFIED BY '{db_password}';
	    exit; 

2. in shell:

		cd {project_dir}
		php run installer

## Test Database ##

In case you plan to run tests, you will need one more database, just for tests. By convention, it should be accessible by the same `db_user` and it should be named `{db_name}_test`.

1. In shell, start MySql using `mysql -u root -p` command and enter these MySql statements:

		CREATE DATABASE {db_name}_test;
	    GRANT ALL PRIVILEGES ON {db_name}_test.* TO '{db_user}';
	    exit; 

2. in shell:

		cd {project_dir}
		php run installer --env=testing
