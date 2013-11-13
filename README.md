Recaptcha
=========

Laravel 4 Recaptcha Package

## Installation
1.  Run  `composer require sb89/recaptcha`.
2.  If asked for a version, use "dev-master" (without the quotes).
3.  Add `'Sb89\Recaptcha\RecaptchaServiceProvider'` to the providers array in `app/config/app.php`.
4.  Run `php artisan config:publish sb89/recaptcha`.
5.  Add your Recaptcha Private and Public keys to `app/config/packages/sb89/recaptcha/config.php`.
6.  Add a language entry to `app/lang/en/validation.php` ('en' will depend on your language) e.g 
`'recaptcha'=>'Recaptcha is incorrect.'`

## Usage
1.  `Form::recaptcha()`
2.  Add `'recaptcha_response_field' => 'required|recaptcha'` to your validation rules.

### Theme
The Recaptcha theme can be specified by using `Form::recaptcha(array('theme'=>'clean'))`.

### SSL
SSL can be specified by using `Form::recaptcha(array('use_ssl'=>true))`.

