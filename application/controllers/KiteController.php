<?php
class KiteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->view->user = Zend_Auth::getInstance()->getIdentity();
        } else {
            throw new Exception('Not logged in!');
        }

    }

    public function indexAction()
    {
        // action body
    }

}

