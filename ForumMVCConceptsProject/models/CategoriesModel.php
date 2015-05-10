<?php

class CategoriesModel extends BaseModel {

    public function getAll() {
        $statement = self::$db->query("select * from categories order by id");

        return $statement->fetch_all(MYSQLI_ASSOC);
    }

    public function createCategory($name, $description) {
        if($name == '' || $description == '') {
            return false;
        }
        $statement = self::$db->prepare(
            "insert into categories(name, description) values(?, ?)");
        $statement->bind_param("ss", $name, $description);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function getCategory($id) {
        $statement = self::$db->prepare("select * from categories where id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();

        return $statement->get_result()->fetch_assoc();
    }

    public function deleteCategory($id) {
        $affectedRows = 0;
        $statement = self::$db->prepare(
            "delete from categories where id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $affectedRows += $statement->affected_rows;

        $statement = self::$db->prepare("select * from questions where category_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $affectedRows += $statement->affected_rows;
        $questions = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $deleteAnswersStatement = self::$db->prepare("delete from answers where question_id = ?");
        $deleteQuestionStatement = self::$db->prepare("delete from questions where id = ?");
        $deleteQuestionTagsStatement = self::$db->prepare("delete from questions_tags where question_id = ?");
        foreach($questions as $question) {
            $deleteAnswersStatement->bind_param("i", $question['id']);
            $deleteAnswersStatement->execute();
            $affectedRows += $deleteAnswersStatement->affected_rows;

            $deleteQuestionStatement->bind_param("i", $question['id']);
            $deleteQuestionStatement->execute();
            $affectedRows += $deleteQuestionStatement->affected_rows;

            $deleteQuestionTagsStatement->bind_param("i", $question['id']);
            $deleteQuestionTagsStatement->execute();
            $affectedRows += $deleteQuestionTagsStatement->affected_rows;
        }

        return $statement->affected_rows > 0;
    }

}