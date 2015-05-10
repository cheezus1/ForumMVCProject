<?php

class CategoriesController extends BaseController {
    private $db;

    public function onInit() {
        $this->title = "Categories";
        $this->db = new CategoriesModel();
    }

    public function index() {
        $this->categories = $this->db->getAll();

        $this->renderView();
    }

    public function create() {
        $this->authorize();
        if($this->isPost) {
            $name = $this->testInput($_POST['name']);
            $description = $this->testInput($_POST['description']);
            if($this->db->createCategory($name, $description)) {
                $this->addInfoMessage("Category created.");
                $this->redirect('categories');
            } else {
                $this->addErrorMessage("Error creating category.");
            }
        }

        $this->renderView(__FUNCTION__);
    }

    public function view($id) {
        $this->category = $this->db->getCategory($id);

        $this->renderView(__FUNCTION__);
    }

    public function delete($id) {
        $this->authorize();
        if($this->db->deleteCategory($id)) {
            $this->addInfoMessage("Category deleted.");
        } else {
            $this->addErrorMessage('Error deleting category.');
        }
        $this->redirect('categories');
    }

}