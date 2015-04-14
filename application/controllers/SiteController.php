<?php

class SiteController extends Zend_Controller_Action
{

    protected $_startTime;

    protected $_endTime;

    protected $_totalTime;

    public function preDispatch ()
    {
        parent::preDispatch();
        $this->_startTime = microtime(true);
    }

    public function postDispatch ()
    {
        parent::postDispatch();
        $this->_endTime = microtime(true);
        $this->_totalTime = $this->_endTime - $this->_startTime;
        $this->view->totalTime = number_format($this->_totalTime, 4);
    }

    public function init ()
    {
        $this->_helper->layout->disableLayout();
    }

    public function viewAction ()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
        } else {
            $user = $this->_getParam('owner');
        }
        
        $site = new Application_Model_SiteMapper();
        
        $result = $site->fetchAll($user);
        
        $this->view->user = $result->user;
        $this->view->state = $result->state;
    }

    public function loremAction ()
    {}
}