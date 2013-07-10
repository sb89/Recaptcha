<?php

namespace Sb89\Recaptcha;

use Illuminate\Support\ServiceProvider;

define("RECAPTCHA_API_SERVER", "http://www.google.com/recaptcha/api");
define("RECAPTCHA_API_SECURE_SERVER", "https://www.google.com/recaptcha/api");

class RecaptchaServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->package('sb89/recaptcha');
        $this->createFormMacro();
        $this->createValidator();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return array('recaptcha');
    }

    /**
     * Creates a custom Form macro
     * @return void
     */
    public function createFormMacro() {

        app('form')->macro('recaptcha', function($options=array()) {
            $server = RECAPTCHA_API_SERVER;
            
            if(in_array("use_ssl", $options) && $options["use_ssl"] == true){
                $server = RECAPTCHA_API_SECURE_SERVER;
            }
            
            $pubkey = app('config')->get('recaptcha::public_key');
            
            return app('view')->make('recaptcha::recaptcha', array('public_key'=>$pubkey, 'server'=>$server, 'options'=>$options));
        });
    }
    
    /**
     * Creates a custom validator
     * @return void
     */
    public function createValidator(){
        app('validator')->extend('recaptcha', function($attribute, $value, $parameters){
            $privateKey = app('config')->get('recaptcha::private_key');
            $remoteIP = app('request')->getClientIp();
            $challenge = app('input')->get('recaptcha_challenge_field');
            $response = app('input')->get('recaptcha_response_field');
            
            $recpatcha = new Recaptcha($privateKey, $remoteIP, $challenge, $response);
            
            return $recpatcha->checkAnswer();
        });
    }

}