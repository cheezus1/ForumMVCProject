<?php

abstract class BaseController {

    protected $controllerName;
    protected $layoutName = DEFAULT_LAYOUT;
    protected $isViewRendered = false;
    protected $isPost = false;
    protected $user;
    protected $isLoggedIn;

    function __construct($controllerName) {
        $this->controllerName = $controllerName;
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->isPost = true;
        }

        if(isset($_SESSION['username'])) {
            $this->isLoggedIn = true;
        }

        $this->onInit();
    }

    public function index() {

    }

    public function onInit() {

    }

    public function renderView($viewName = 'index', $includeLayout = true) {
        if(!$this->isViewRendered) {
            $viewFileName = "views/" . $this->controllerName
                . "/" . $viewName . ".php";
            if($includeLayout) {
                $headerFile = "/views/layouts/" . $this->layoutName . '/header.php';
                include_once($headerFile);
            }
            include_once($viewFileName);
            if($includeLayout) {
                $footerFile = "/views/layouts/" . $this->layoutName . '/footer.php';
                include_once($footerFile);
            }
            $this->isViewRendered = true;
        }
    }

    public function redirectToUrl($url) {
        header("Location: " . $url);
        die;
    }

    public function redirect($controllerName, $actionName = null, $params = null) {
        $url = '/' . urlencode($controllerName);
        if($actionName != null) {
            $url .= '/' . urlencode($actionName);
        }
        if($params != null) {
            $encodedParams = array_map($params, 'urlencode');
            $url .= implode('/', $encodedParams);
        }

        $this->redirectToUrl($url);
    }

    public function getUser() {
    }

    public function authorize() {
        if(!$this->isLoggedIn) {
            $this->addErrorMessage('Please login first.');
            $this->redirect('account', 'login');
        }
    }

    protected function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function addMessage($msg, $type) {
        if(!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }
        array_push($_SESSION['messages'], array('text' => $msg, 'type' => $type));
    }

    public function addInfoMessage($msg) {
        $this->addMessage($msg, 'success');
    }

    public function addErrorMessage($msg) {
        $this->addMessage($msg, 'danger');
    }


}