# Creating New Project From Template #

1. In shell:

		cd {home_dir}
		composer create-project -n dubysa/dubysa "--repository={\"type\":\"vcs\",\"url\":\"git@bitbucket.org:dubysa/dubysa.git\"}" {project}

2. (Optional) You can check that project files are created successfully:

		cd {project}
		php run

	You should see list of Dubysa shell commands containing `installer` command, `migrations` command, and many more.

