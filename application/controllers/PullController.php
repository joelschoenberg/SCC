<?php

class PullController extends Zend_Controller_Action
{
    protected $_user;

    protected $_key;

    protected $_secret;

    protected $_session;

    public function preDispatch()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            $this->_helper->redirector('index', 'login');
        }
    }

    public function init()
    {
        $this->_helper->layout->setLayout('basic');

        $this->_session = new Zend_Session_Namespace('Catchpoint');

        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;
        $user = new Application_Model_SiteMapper();

        $result = $user->fetchAll($this->_user);

        if ($result->key == null) {
            throw new Exception('No Key/Secret set!');
        } else {
            $this->_key = $result->key;
            $this->_secret = $result->secret;
            $this->view->key = (empty($result->key)) ? '' : base64_decode($result->key);
            $this->view->secret = (empty($result->secret)) ? '' : base64_decode($result->secret);
            $this->view->chartId = (empty($result->chart_id)) ? '' : $result->chart_id;
        }

    }

    public function indexAction()
    {
    }

    public function apiAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->_getParam('call');

        $t = new Catchpoint_Pull();
        $t->override = true;
        $t->key = $this->_key;
        $t->secret = $this->_secret;

        $result = $t->fetchData($request);
        print_r ($result);
    }
}
