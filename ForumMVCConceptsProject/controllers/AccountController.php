<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 5/8/2015
 * Time: 9:38 PM
 */

class AccountController extends BaseController {

    private $db;

    public function onInit() {
        $this->title = "Account";
        $this->db = new AccountModel();
    }

    public function register() {
        if($this->isPost) {
            if(isset($_SESSION['username'])) {
                $this->logout();
            }
            if(!$this->registerInputIsEmpty() && $this->inputIsValid() && $this->registerPasswordsMatch()) {
                $username = $this->testInput($_POST['username']);
                $password = $this->testInput($_POST['password']);
                $firstname = $this->testInput($_POST['firstname']);
                $lastname = $this->testInput($_POST['lastname']);
                $email = $this->testInput($_POST['email']);
                $pictureUrl = "content/images/";

                if($_FILES["fileToUpload"]["name"] != null) {
                    $pictureUrl .= basename($_FILES["fileToUpload"]["name"]);
                    if(!$this->uploadImage()) {
                        unlink($pictureUrl);
                        $this->renderView(__FUNCTION__);
                    }
                } else {
                    $pictureUrl .= "default-profile-picture.png";
                }
                if($username == null || strlen($username) < 3) {
                    $this->addErrorMessage("Username is invalid.");
                    $this->redirect('account', 'register');
                }
                $isRegistered = $this->db->register($username, $password, $firstname, $lastname, $email, $pictureUrl);
                if($isRegistered) {
                    $this->addInfoMessage('Successful registration.');
                    $this->redirect('account', 'login');
                } else {
                    $this->addErrorMessage('Register failed.');
                }
            }
        }

        $this->renderView(__FUNCTION__);
    }

    public function login() {
        if($this->isPost) {
            if(isset($_SESSION['username'])) {
                $this->logout();
            }
            $username = $this->testInput($_POST['username']);
            $password = $this->testInput($_POST['password']);
            $userId = $this->db->login($username, $password);
            if($userId != -1) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $userId;
                $this->addInfoMessage('Successful login.');
                return $this->redirect("categories", "index");
            } else {
                $this->addErrorMessage('Wrong username/password.');
            }
        }

        $this->renderView(__FUNCTION__);
    }

    public function logout() {
        $this->authorize();

        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['picture_url']);
        $this->addInfoMessage('Logout successful.');
        $this->redirectToUrl('/');
    }

    public function profile($id) {
        $this->user = $this->db->getUser($id);
        $this->renderView(__FUNCTION__);
    }

    private function registerInputIsEmpty() {
        if($this->testInput($_POST['username']) == '') {
            $this->addErrorMessage("Username is required.");
            return true;
        }
        if($this->testInput($_POST['password']) == '') {
            $this->addErrorMessage("Password is required.");
            return true;
        }
        if($this->testInput($_POST['confirmed-password']) == '') {
            $this->addErrorMessage("You must confirm the password.");
            return true;
        }
        if($this->testInput($_POST['firstname']) == '') {
            $this->addErrorMessage("Firstname is required.");
            return true;
        }
        if($this->testInput($_POST['lastname']) == '') {
            $this->addErrorMessage("Lastname is required.");
            return true;
        }
        if($this->testInput($_POST['email']) == '') {
            $this->addErrorMessage("Email is required.");
            return true;
        }

        return false;
    }

    private function inputIsValid() {
        $username = $this->testInput($_POST['username']);
        $password = $this->testInput($_POST['password']);
        $firstname = $this->testInput($_POST['firstname']);
        $lastname = $this->testInput($_POST['lastname']);
        $email = $this->testInput($_POST['email']);

        if (!preg_match('/^[a-zA-Z0-9@_]*$/', $username)) {
            $this->addErrorMessage('Incorrect username format (only alpha, numbers, @_ are allowed).');
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9@_]*$/', $password)) {
            $this->addErrorMessage('Incorrect password format (only alpha, numbers, @_ are allowed).');
            return false;
        }
        if(!preg_match("/^[a-zA-Z'-]+$/", $firstname) || !preg_match("/^[a-zA-Z'-]+$/", $lastname)) {
            $this->addErrorMessage('Incorrect name format (only alpha, \'- are allowed).');
            return false;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorMessage('Incorrect email format.');
            return false;
        }

        return true;
    }

    private function registerPasswordsMatch() {
        if($this->testInput($_POST['password']) === $this->testInput($_POST['confirmed-password'])) {
            return true;
        }

        $this->addErrorMessage("Passwords do not match.");
        return false;
    }

    private function uploadImage() {
        $target_dir = "content/images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $this->addErrorMessage("File is not an image.");
                $uploadOk = 0;
                return false;
            }
        }
        if (file_exists($target_file)) {
            $this->addErrorMessage("Sorry, file already exists.");
            $uploadOk = 0;
            return false;
        }
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $this->addErrorMessage("Sorry, your file is too large.");
            $uploadOk = 0;
            return false;
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $this->addErrorMessage("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            $uploadOk = 0;
            return false;
        }
        if ($uploadOk == 0) {
            $this->addErrorMessage("Sorry, your file was not uploaded.");
            return false;
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                return true;
            } else {
                $this->addErrorMessage("Sorry, there was an error uploading your file.");
                return false;
            }
        }
    }

}