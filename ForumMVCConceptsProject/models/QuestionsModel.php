<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 5/8/2015
 * Time: 10:59 PM
 */

class QuestionsModel extends BaseModel {

    public function getAll() {
        $statement = self::$db->query("select q.id, q.from_user, q.title, q.content, c.name,
             q.number_of_visits, u.username, q.category_id, u.picture_url
             from questions q join categories c on c.id = q.category_id join users u on u.id = q.from_user
             order by id");
        return $statement->fetch_all(MYSQLI_ASSOC);
    }

    public function getQuestion($id) {
        $statement = self::$db->prepare(
            "select q.id, q.from_user, q.title, q.content, c.name, q.number_of_visits, u.username, q.category_id, u.picture_url
             from questions q join categories c on c.id = q.category_id join users u on u.id = q.from_user
             where q.id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();

        return $statement->get_result()->fetch_assoc();
    }

    public function getQuestionsForCategory($categoryId) {
        $statement = self::$db->prepare(
            "select q.id, q.from_user, q.title, q.content, c.name, q.number_of_visits, u.username, q.category_id, u.picture_url
             from questions q join categories c on c.id = q.category_id join users u on u.id = q.from_user
             where c.id = ?");
        $statement->bind_param("i", $categoryId);
        $statement->execute();

        return $statement->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getQuestionsByTags($tags) {
        $statement = self::$db->query(
            "select q.id, q.from_user, q.title, q.content, c.name, q.number_of_visits, u.username, q.category_id, u.picture_url
             from questions q
             join categories c on c.id = q.category_id
             join users u on u.id = q.from_user
             join questions_tags qt on q.id = qt.question_id
             join tags t on t.id = qt.tag_id
             where " . $this->buildTagCriteriaString($tags) . " and t.id = qt.tag_id and q.id = qt.question_id");

        return $statement->fetch_all(MYSQLI_ASSOC);
    }

    public function getQuestionId($title) {
        $statement = self::$db->prepare("select id from questions where title = ?");
        $statement->bind_param("s", $title);
        $statement->execute();

        return $statement->get_result()->fetch_assoc()['id'];
    }

    public function createQuestion($title, $content, $categoryId) {
        $statement = self::$db->prepare('insert into questions (from_user, title, content, category_id) values(?, ?, ?, ?)');
        $statement->bind_param("issi", $_SESSION['user_id'], $title, $content, $categoryId);
        $statement->execute();

        return $statement->affected_rows > 0;
    }

    public function deleteQuestion($id) {
        $affectedRows = 0;

        $statement = self::$db->prepare("delete from answers where question_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $affectedRows += $statement->affected_rows;

        $statement = self::$db->prepare("delete from questions where id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $affectedRows += $statement->affected_rows;

        $statement = self::$db->prepare("delete from questions_tags where question_id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $affectedRows += $statement->affected_rows;

        return $affectedRows > 0;
    }

    public function createQuestionTags($questionId, $tags) {
        $selectTagStatement = self::$db->prepare('select * from tags where name = ?');
        $createTagStatement = self::$db->prepare('insert into tags (name) values(?)');
        $createQuestionsTagsStatement = self::$db->prepare("insert into questions_tags(question_id, tag_id) values(?, ?)");
        foreach($tags as $tag) {
            $selectTagStatement->bind_param("s", $tag);
            $selectTagStatement->execute();
            $currentTag = $selectTagStatement->get_result()->fetch_assoc();
            if($currentTag == null) {
                $createTagStatement->bind_param("s", $tag);
                $createTagStatement->execute();
                $selectTagStatement->execute();
                $currentTag = $selectTagStatement->get_result()->fetch_assoc();
                $createQuestionsTagsStatement->bind_param("ii", $questionId, $currentTag['id']);
                $createQuestionsTagsStatement->execute();
            }
        }
    }

    public function incrementQuestionNumberOfVisits($id) {
        $statement = self::$db->prepare("select number_of_visits from questions where id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();

        $numberOfVisits = $statement->get_result()->fetch_assoc()['number_of_visits'] + 1;
        $statement = self::$db->prepare("update questions set number_of_visits = ? where id = ?");
        $statement->bind_param("ii", $numberOfVisits, $id);
        $statement->execute();
    }

    private function buildTagCriteriaString($tags) {
        $builtString = "";
        for($i = 0; $i < count($tags) - 1; $i++) {
            $builtString .= " t.name like '%" . $tags[$i] . "%' or ";
        }

        $builtString .= " t.name like '%" . $tags[count($tags) - 1] . "%'";

        return $builtString;
    }
}