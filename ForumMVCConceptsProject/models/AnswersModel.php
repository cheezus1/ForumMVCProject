<?php
class AnswersModel extends BaseModel {

    public function getAll() {
        $statement = self::$db->query("select * from answers order by id");

        return $statement->fetch_all(MYSQLI_ASSOC);
    }

    public function getAnswersForQuestion($questionId) {
        $statement = self::$db->prepare(
            "select a.id, a.content, a.author_firstname, a.author_lastname, a.email, a.author_id, a.question_id,
             u.username, u.is_registered, q.title, u.picture_url
             from answers a join users u on u.id = a.author_id join questions q on q.id = a.question_id
             where a.question_id = ?
             order by a.id");
        $statement->bind_param("i", $questionId);
        $statement->execute();

        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createAnswerByUser($questionId, $content) {
        $statement = self::$db->prepare("select firstname, lastname, email from users where id = ?");
        $statement->bind_param("i", $_SESSION['user_id']);
        $statement->execute();
        $userData = $statement->get_result()->fetch_assoc();
        var_dump($userData);
        $statement = self::$db->prepare(
            "insert into answers (content, author_firstname, author_lastname, email, author_id, question_id)
             values(?, ?, ?, ?, ?, ?)");
        $statement->bind_param("ssssii", $content, $userData['firstname'],
            $userData['lastname'], $userData['email'], $_SESSION['user_id'], $questionId);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function createAnswerByVisitor($questionId, $content, $firstname, $lastname, $email) {
        $statement = self::$db->prepare("insert into users(firstname, lastname, email, is_registered) values (?, ?, ?, 0)");
        $statement->bind_param("sss", $firstname, $lastname, $email);
        $statement->execute();

        $statement = self::$db->prepare("select id from users
            where firstname = ? and lastname = ? and email = ? and is_registered = 0");
        $statement->bind_param("sss", $firstname, $lastname, $email);
        $statement->execute();
        $userId = $statement->get_result()->fetch_assoc();

        $statement = self::$db->prepare(
            "insert into answers (content, author_firstname, author_lastname, email, author_id, question_id)
             values(?, ?, ?, ?, ?, ?)");
        $statement->bind_param("ssssii", $content, $firstname, $lastname, $email, $userId['id'], $questionId);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function deleteAnswer($id) {
        $statement = self::$db->prepare("delete from answers where id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

}