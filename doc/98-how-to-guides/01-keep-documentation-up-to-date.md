# Keep Documentation Up-To-Date #

## Setup ##

1. Add a documentation topic.
2. Add documentation test, example `\Manadev\Tests\DocumentationTest::test_web_developer_guide()`:

        class DocumentationTest extends DocumentationTestCase
        {
            public function test_web_developer_guide() {
                $this->assertDocumentationIsUpToDate('web-development/css-styles/reference/sass-variables',
                    'c4790db', [
                        'src/*/*/resources/css/_variables.scss',
                    ]);
            }
        } 

    `assertDocumentationIsUpToDate()` checks if listed source files `src/*/*/resources/css/_variables.scss` changed since commit `c4790db` and if so, informs you about changes.

    Initially, pass `null` to second parameter to check all Git commits. 

3. [if needed] Update PHPUnit configuration using `php run config:phpunit` command.
4. Run PHPUnit, you should see that documentation test fails:

        Documentation under 'web-development/css-styles/reference/sass-variables' URL is outdated.
        
        Review the following undocumented commits: 
         c4790db 2018-10-21 Vladislav Osmianskij: SASS variables and their documentation reviewed
         58af126 2018-10-21 Vladislav Osmianskij: Outdated documentation testing works; documentation changes
         1c901df 2018-09-04 Vladislav Osmianskij: CSS, layer publishing, JS behaviors, Ui/Aba
        
        Review the following undocumented file changes: 
         M src/Ui/Aba/resources/css/_variables.scss
        
        Use the following commands to review undocumented changes visually:
         cd vendor/dubysa/components
         gitk  -- src/*/*/resources/css/_variables.scss
        
        After updating documentation, set '$since' parameter to latest commit ID.

5. If there is `Review the following undocumented file changes` section, first commit all source files and run PHPUnit again. You should see that that section is gone.
6. Pass latest commit ID to second parameter in `assertDocumentationIsUpToDate()` call and run test again. It should pass. 

## Updates ##

1. As source files evolve, eventually, documentation tests will fail: 


        Documentation under 'web-development/css-styles/reference/sass-variables' URL is outdated.
        
        Review the following undocumented commits: 
         c4790db 2018-10-21 Vladislav Osmianskij: SASS variables and their documentation reviewed
         58af126 2018-10-21 Vladislav Osmianskij: Outdated documentation testing works; documentation changes
         1c901df 2018-09-04 Vladislav Osmianskij: CSS, layer publishing, JS behaviors, Ui/Aba
        
        Review the following undocumented file changes: 
         M src/Ui/Aba/resources/css/_variables.scss
        
        Use the following commands to review undocumented changes visually:
         cd vendor/dubysa/components
         gitk  -- src/*/*/resources/css/_variables.scss
        
        After updating documentation, set '$since' parameter to latest commit ID.

2. If there is `Review the following undocumented file changes` section, first commit all source files and run PHPUnit again. You should see that that section is gone.
3. Run listed `cd` and `gitk` commands as described in test message, analyze the changes and update documentation.  
4. Pass latest commit ID to second parameter in `assertDocumentationIsUpToDate()` call and run test again. It should pass. 
