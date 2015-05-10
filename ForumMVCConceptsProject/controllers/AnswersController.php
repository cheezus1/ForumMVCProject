<?php

class AnswersController extends BaseController {

    public function onInit() {
        $this->title = "Answers";
        $this->db = new AnswersModel();
    }

    public function index() {
        $this->answers = $this->db->getAll();
        $this->renderView(__FUNCTION__);
    }

    public function showAnswersForQuestion($questionId) {
        $this->answers = $this->db->getAnswersForQuestion($questionId);
        $this->renderView(__FUNCTION__, false);
    }

    public function create($questionId) {
        if($this->isPost) {
            $content = $this->testInput($_POST['content']);
            if(!isset($_POST['firstname'])) {
                if($this->db->createAnswerByUser($questionId, $content)) {
                    $this->addInfoMessage("Answer added successfuly.");
                    $this->redirectToUrl("/questions/view/$questionId");
                } else {
                    $this->addErrorMessage("Error adding answer.");
                }
            } else {
                $firstname = $this->testInput($_POST['firstname']);
                $lastname = $this->testInput($_POST['lastname']);
                $email = $this->testInput($_POST['email']);
                if($this->db->createAnswerByVisitor($questionId, $content, $firstname, $lastname, $email)) {
                    $this->addInfoMessage("Answer added successfuly.");
                    $this->redirectToUrl("/questions/view/$questionId");
                } else {
                    $this->addErrorMessage("Error adding answer.");
                }
            }
        }

        $this->renderView(__FUNCTION__);
    }

    public function delete($id) {
        $this->authorize();
        if($this->db->deleteAnswer($id)) {
            $this->addInfoMessage("Answer deleted.");
        } else {
            $this->addErrorMessage('Error deleting answer.');
        }
        $this->redirect('questions');
    }

}