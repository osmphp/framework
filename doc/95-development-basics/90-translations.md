# Translations
Custom module can support translations. 

1. To configure module translation directory `/config/translations` should be created in module directory. 
2. For every supported language and locale own file should be created. For example file name `en_US.php` will be used for English language and USA locale. File should contain original text and translation like in example below:

		<?php
		
		return [
		    "Original text" => "Translated text",
		];

3. Application locale is configured in `APP_LOCALE` parameter of `.env` file and can be changed. If file for this locale will not be found in module `/config/translations` directory, `en_US` will be used. 

4. Function `m_` provide text translated to current environment locale.

For example `m_("Accounts")` used in specific module will return original text `Accounts`.

Keep in mind that double quotes are used to enclose the text. This is needed to provide correct processing of placeholders.

* current environment locale is `en_US`
* or there is no translation file for current environment locale in `/config/translations` directory under current module   
* or given text cannot be found in translation file

Otherwise translated text will be returned.