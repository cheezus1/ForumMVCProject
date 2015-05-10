<?php

class QuestionsController extends BaseController {

    public function onInit() {
        $this->title = "Questions";
        $this->db = new QuestionsModel();
    }

    public function index() {
        $this->questions = $this->db->getAll();

        $this->renderView();
    }

    public function view($id) {
        $this->db->incrementQuestionNumberOfVisits($id);
        $this->question = $this->db->getQuestion($id);

        $this->renderView(__FUNCTION__);
    }

    public function create($categoryId) {
        $this->authorize();
        if($this->isPost) {
            $title = $this->testInput($_POST['title']);
            $content = $this->testInput($_POST['content']);
            $parsedTags = explode(',', $_POST['tags']);
            $parsedTags = array_map('trim', $parsedTags);
            if($this->tagsFormatIsValid($parsedTags)) {
                if ($this->db->createQuestion($title, $content, $categoryId)) {
                    $createdQuestionId = $this->db->getQuestionId($title);
                    $this->db->createQuestionTags($createdQuestionId, $parsedTags);
                    $this->addInfoMessage("Question added successfully.");
                    $this->redirectToUrl('/questions/view/' . $createdQuestionId);
                } else {
                    $this->addErrorMessage("Error adding question.");
                }
            }
        }

        $this->renderView(__FUNCTION__);
    }

    public function delete($id) {
        $this->authorize();
        if($this->db->deleteQuestion($id)) {
            $this->addInfoMessage("Question deleted.");
        } else {
            $this->addErrorMessage('Error deleting question.');
        }
        $this->redirect('questions');
    }

    public function showQuestionsForCategory($categoryId) {
        $this->questions = $this->db->getQuestionsForCategory($categoryId);
        $this->renderView(__FUNCTION__, false);
    }

    public function showQuestionsByTags() {
        $tags = explode(' ', $this->testInput($_GET['search-input']));
        $this->questions = $this->db->getQuestionsByTags($tags);
        $this->tags = $tags;
        $this->renderView(__FUNCTION__);
    }

    private function tagsFormatIsValid($tags) {
        foreach($tags as $tag) {
            if(!preg_match('/^[a-zA-Z0-9_]*$/', $tag)) {
                $this->addErrorMessage('Invalid tag format (only alpha, numbers, @_ are allowed).');
                return false;
            }
        }

        return true;
    }
}