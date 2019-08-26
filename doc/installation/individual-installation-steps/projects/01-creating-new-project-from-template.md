# Creating New Project From Template #

1. In shell:

		cd {home_dir}
		composer create-project -n osmphp/osmproject "--repository={\"type\":\"vcs\",\"url\":\"git@bitbucket.org:osmphp/osmproject.git\"}" {project}

2. (Optional) You can check that project files are created successfully:

		cd {project}
		php run

	You should see list of Osm shell commands containing `installer` command, `migrations` command, and many more.

