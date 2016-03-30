<?php

class SiteController extends Zend_Controller_Action
{
    protected $_startTime;

    protected $_endTime;

    public function preDispatch()
    {
        parent::preDispatch();
        $this->_startTime = microtime(true);
    }

    public function postDispatch()
    {
        parent::postDispatch();
        $this->_endTime = microtime(true);
        $totalTime = $this->_endTime - $this->_startTime;
        $this->view->totalTime = number_format($totalTime, 4);
    }

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
        if ($this->_getParam('timeout')) {
          $this->view->timeout = $this->_getParam('timeout');
        } else {
          $this->view->timeout = 0;
        }
        $site = new Application_Model_SiteMapper();

        $result = $site->fetchAll($user);

        if(!$result) {
          throw new Exception('User not found!');
        }

        $this->view->user  = $result['user'];
        $this->view->state = $result['state'];
    }

    public function loremAction()
    {
    }
}
