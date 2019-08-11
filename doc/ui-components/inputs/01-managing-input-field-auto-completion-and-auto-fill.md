# How-To: Managing Input Field Auto-Completion And Auto-Fill #

By default, browser remembers what you enter into a field and next time opens drop down suggesting previously entered value. To disable this behavior, set input view `autocomplete` property to `off`:

    Input::new([
        'name' => 'email',
        'title' => m_("Email"),
        'modifier' => '-filled',
        'autocomplete' => off,
    ]),

By default, input fields are configured not to remember any passwords entered into form. In login form, you may disable this behavior by setting input view `autocomplete` property to `null`:

    'password' => Input::new([
            'name' => 'password',
            'title' => m_("Password"),
            'modifier' => '-filled -password',
            'required' => true,
			'autocomplete' => null,
    ]),
