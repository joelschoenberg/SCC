<?php

class SettingsController extends Zend_Controller_Action
{
    protected $_user;

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
        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
    }

    public function saveAction()
    {
        $settings = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'key' => $this->_getParam('key'),
                        'secret' => $this->_getParam('secret'),
                        'chartId' => $this->_getParam('chart_id')
                ));

        $mapper = new Application_Model_SiteMapper();

        $mapper->settings($settings);

        $this->_helper->redirector('index', 'index');
    }
}
