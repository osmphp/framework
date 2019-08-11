# Cloning Existing Project #

1. In shell:

		cd {home_dir}
		git clone {project_repo_url} {project}

2. Alternatively, if you want to clone specific branch (e.g. `live` branch):

		cd {home_dir}
		git clone {project_repo_url} {project} --branch live

3. Install environment files and the dependencies:

		cd {project}
		composer run-script post-root-package-install
		composer install
		composer run-script post-update-cmd

4. (Optional) You can check that project files are created successfully:

		php run

	You should see list of Dubysa shell commands containing `installer` command, `migrations` command, and many more.
