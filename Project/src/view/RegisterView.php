<?php

namespace view;

use \common\Messages;

class RegisterView {

    private static $username = "RegisterView::UserName";
    private static $password = "RegisterView::Password";
    private static $passwordRepeat = "RegisterView::PasswordRepeat";
    private static $registration = "RegisterView::Register";
    private static $messageId = 'RegisterView::Message';

    private $message;

    public function response() {
        $message = $this->message;

        return $this->generateRegistrationFormHTML($message);
    }

    private function generateRegistrationFormHTML($message){

        if($message != ""){
            $messageContainer = '<div class="checkoutMessage "><p class="alert alert-danger" id="' . self::$messageId . '">' . $message . '</p></div>';
        }
        else{
            $messageContainer = '<p" id="' . self::$messageId . '">' . $message . '</p>';
        }

    return "
            <div class='jumbotron'>
                <h2>Register new user</h2>
                <form method='post' >
                    <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                      <div class='normal-font'>
                        $messageContainer
                      </div>
                      <div class='form-group'>
                      <label for='" . self::$username . "' >Username :</label>
                      <input type='text' size='20' name='" . self::$username . "' id='" . self::$username . "' value='" . strip_tags($this->getRegisterUsername())  ."' />
                      </div>
                      <div class='form-group'>
                      <label for='" . self::$password . "' >Password  :</label>
                      <input type='password' size='20' name='" . self::$password . "' id='" . self::$password . "' value='' />
                      </div>
                      <div class='form-group'>
                      <label for='" . self::$passwordRepeat . "' >Repeat password  :</label>
                      <input type='password' size='20' name='" . self::$passwordRepeat . "' id='" . self::$passwordRepeat . "' value='' />
                      </div>
                      <div class='form-group'>
                      <input id='submit' type='submit' name='" . self::$registration . "'  value='Register' />
                      </div>
                    </fieldset>
                </form>
            </div>

    		";
    }

    public function registerAttempt() {
        return isset($_POST[self::$registration]);
    }

    public function getRegisterUsername(){
        if(isset($_POST[self::$username])) {
            return $_POST[self::$username];
        }
        return null;
    }

    public function getRegisterPassword(){
        if(isset($_POST[self::$password])) {
            return $_POST[self::$password];
        }
    }

    public function getRegisterPasswordRepeat(){
        if(isset($_POST[self::$passwordRepeat])) {
            return $_POST[self::$passwordRepeat];
        }
    }

    public function getRegistrationInfo()
    {
        $message = "";
        $canIRegisterNewUser = true;

        if(strlen($this->getRegisterUsername()) < 3){

            $message .= Messages::$usernameIsNotCorrect . "<br>";
            $canIRegisterNewUser = false;
        }
        if(strlen($this->getRegisterPassword()) < 6){
            $message .= Messages::$passwordIsNotCorrect;
            $canIRegisterNewUser = false;
        }
        if($this->getRegisterPassword() !== $_POST[self::$passwordRepeat]){
            $message .= Messages::$passwordIsNotSame;
            $canIRegisterNewUser = false;
        }
        if($this->getRegisterUsername() !== strip_tags($this->getRegisterUsername())){
            $message .= Messages::$forbiddenCharacters;
            $canIRegisterNewUser = false;
        }

        $this->message = $message;

        if($canIRegisterNewUser){
            return new \model\UserModel($this->getRegisterUsername(), $this->getRegisterPassword());
        }
        else{
            return null;
        }
    }

    public function userJustRegistered(bool $didUserJustRegister){
        return $didUserJustRegister;
    }

    //Set message to show user
    public function setMessage($message){
        assert(is_string($message));
        return $this->message = $message;
    }


}