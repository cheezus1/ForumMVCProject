<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 5/8/2015
 * Time: 9:38 PM
 */

class AccountModel extends BaseModel {

    public function register($username, $password, $firstname, $lastname, $email, $pictureUrl) {
        $statement = self::$db->prepare("select count(id) from users where username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        if($result['count(id)']) {
            return false;
        }

        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $registerStatement = self::$db->prepare(
            "insert into users (username, password, firstname, lastname, email, picture_url, is_registered) values (?, ?, ?, ?, ?, ?, 1)");
        $registerStatement->bind_param("ssssss", $username, $hashPass, $firstname, $lastname, $email,  $pictureUrl);
        $registerStatement->execute();

        return true;
    }

    public function login($username, $password) {
        $statement = self::$db->prepare("select id, username, password, picture_url from users where username = ?");
        $statement->bind_param("s", $username);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        if(password_verify($password, $result['password'])) {
            $_SESSION['picture_url'] = $result['picture_url'];
            return $result['id'];
        }

        return -1;
    }

    public function getUser($id) {
        $statement = self::$db->prepare("select username, firstname, lastname, email, picture_url from users where id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();

        return $statement->get_result()->fetch_assoc();
    }

}