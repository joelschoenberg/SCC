<?php
class SiteController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->disableLayout();
    }

    public function viewAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
        } else {
            $user = $this->_getParam('owner');
        }

        $site = new Application_Model_SiteMapper();

        $result = $site->fetchAll($user);

        $this->view->user  = $result->user;
        $this->view->state = $result->state;
    }

    public function loremAction()
    {

    }

}