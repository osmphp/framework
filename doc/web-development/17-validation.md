# Validation #

All incoming HTTP requests should be validated on server. While [routing parameter definitions](#) handle validation of HTTP query parameters, payload of incoming HTTP requests is validated using `Osm\Framework\Validation\Validator` class. 

## Sample Request ##

Consider the typical login form:

![Login form](login-form.png)

When submitted, this form sends AJAX request:

	POST /login
	{
		"email":"my@email.com",
		"password":"my_password"
	}

>**Note.** The notation used above means that server received `POST` request with URL path `/login` and payload containing JSON with user credentials.

## Request Handing ##

This request is handled using route:

	// config/routes/frontend.php

	return [
	    'POST /login' => [
	        'class' => Frontend::class,
	        'method' => 'login',
	    ],
	];

Mentioned controller class (among other things) should validate incoming payload:

	/**
	 * @property Validator $validator @required
	 */
	class Frontend extends Controller
	{
	    protected function default($property) {
	        global $m_app; /* @var App $m_app */
	
	        switch ($property) {
	            case 'validator': return $m_app[Validator::class];
	        }
	        return parent::default($property);
	    }
	
	    public function login() {
	        /* @var LoginPayloadHint $payload */
	        $payload = $this->validator->validate(
				json_decode($this->request->content), 
				LoginPayloadHint::class
			);
	
			// other logic
	    }

		// other methods
	}	

>**Note**. `$this->request` is standard property available in every controller class. It contains incoming request object.

## Validation Rules ##

Call to `$this->validator->validate()` checks if incoming payload conforms to all validation rules specified in `LoginPayloadHint` class:

	/**
	 * @property string $email @required
	 * @property string $password @required
	 * @property string $referer
	 */
	abstract class LoginPayloadHint
	{
	
	}

In this example, `LoginPayloadHint` class defines 2 validation rules:

* property `email` should be present and contain non-empty string 
* property `password` should be present and contain non-empty string 

## Reporting Failed Validation ##

If any of these rules fail, `ValidationFailed` exception is thrown containing information about which fields didn't pass the validation and error messages for each such field to be shown to the user. This exception is automatically translated into HTTP error response:

	400 Validation failed
	{
	    "error": "validation_failed",
	    "messages": {
	        "email": "Email expected",
	        "password": "Password expected"
	    }
	}

>**Note**. The notation used above means response with status code `400`, status text `Validation failed` and payload containing JSON with validation messages.

Such responses are processed by client-side form script and display validation messages near each input field.

## Using Validated Data ##

If all validation rules pass, call to `$this->validator->validate()` return `stdClass` object. It is convenient to type-hint it to the same `LoginPayloadHint` class and allow PHPStorm auto-completion to work for its properties:

    /* @var LoginPayloadHint $payload */
    $payload = $this->validator->validate(...);

	// as $payload is type-hinted, IDE offers 
	// auto-completion while typing 'email' and 'password' 
	$this->checkTheUser($payload->email, $payload->password);

## More Validation Rules ##

String properties may have the following validation rules:

* `@required` - checks if property is present and non-empty string
* `@max_length(255)` - checks if value is no longer than specified value in parenthesis
* `@min_length(20)` - checks if value has at least specified number of characters
* `@pattern("email")` - checks if value matches regex pattern specified in `config/validation_patterns.php`. At the moment of writing only `email` and `url_key` patterns are defined. You can add your own patterns by defining the in `config/validation_patterns.php` of your module. 

Integer properties are only checked that incoming value is actually an integer. Currently no other validation rules are available for integers.

## Validating Complex Objects ##

Properties of the hint class used for validation may be of complex types thus allowing to validate nested arrays and objects.