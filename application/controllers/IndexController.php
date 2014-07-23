<?php

class IndexController extends Zend_Controller_Action
{

    protected $_user;
    
    protected $_state;

    public function preDispatch ()
    {
        if (! Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            $this->_helper->redirector('index', 'login');
        }
    }

    public function init ()
    {
        $this->_user = Zend_Auth::getInstance()->getIdentity();
        //$this->view->user = $this->_user;
        
        $site = new Application_Model_SiteMapper();
        
        $result = $site->fetchAll($this->_user);
        
        $this->view->user = $result->user;
        $this->_state = $result->state;
    }

    public function setAction ()
    {
        $this->state = $this->_getParam('state');
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $site = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'state' => $this->state
                ));
        
        $mapper = new Application_Model_SiteMapper();
        
        $mapper->save($site);
    }

    public function indexAction ()
    {
        //$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->renderScript('site/' . $this->_state . '.phtml');
    }
    public function stateAction ()
    {
        $state = $this->_getParam('type');
        $this->renderScript('site/' . $state . '.phtml');
        $site = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'state' => $state
                ));
        
        $mapper = new Application_Model_SiteMapper();
        
        $mapper->save($site);
    }
}

