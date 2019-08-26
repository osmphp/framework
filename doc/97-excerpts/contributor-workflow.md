# Contributor's Workflow #

**Q**. In `osmphp/framework`, there is `master` branch and `next` branch. Which one should I choose when contributing patches?

**A**. It depends on size and backward compatibility of the patch. Small and backward compatible go to `master` branch, while large pieces with breaking changes go to `next` branch.

**Q**. How do I send the patch if I am core developer?

**A**.

1. Change to `dev-master@dev` in `composer.json` and run `composer update --no-scripts`.
2. Develop your changes in feature branch, commit it and push to `origin`.
3. Create a pull request.
3. After your feature branch is merged and released, switch back to latest stable version in `composer.json` and run `composer update --no-scripts`.

**Q**. How do I send the patch if I am NOT a core developer?

**A**.

1. Create forked repository.
2. Change to `dev-master@dev` in `composer.json`, change repository URL in `composer.json` and run `composer update --no-scripts`.
3. Develop your changes in feature branch, commit it and push to `origin`.
4. Create a pull request.
5. After your feature branch is merged and released, switch back to latest stable version in `composer.json`, switch back to original repository URL in `composer.json` and run `composer update --no-scripts`.
6. Delete forked repository.

