<?php
class IndexController extends Zend_Controller_Action
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
    }

    public function setAction()
    {
        $state = $this->_getParam('state');
        $user = 'dummy';
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $site = new Application_Model_Site(array(
			'user' => $this->_user,
			'state'=> $state)
        );

        $mapper  = new Application_Model_SiteMapper();

        $mapper->save($site);
    }

    public function indexAction()
    {
        // action body
    }


    /*
     public function notfoundAction()
     {
     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender(true);

     $site = new Application_Model_Site(array(
     'user' => $this->_user,
     //'passwd' => '',
     'content'=>'',
     'css'=>'',
     'enabled'=>404)
     //'id'=>1)
     );

     $mapper  = new Application_Model_SiteMapper();

     $mapper->save($site);
     //return $this->_helper->redirector('index');
     }

     public function apperrorAction()
     {
     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender(true);

     $site = new Application_Model_Site(array(
     'user' => $this->_user,
     //'passwd' => '',
     'content'=>'',
     'css'=>'',
     'enabled'=>500)
     //'id'=>1)
     );

     $mapper  = new Application_Model_SiteMapper();

     $mapper->save($site);
     //return $this->_helper->redirector('index');
     }

     public function disableAction()
     {
     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender(true);

     $site = new Application_Model_Site(array(
     'user' => $this->_user,
     //'passwd' => '',
     'content'=>'The text on this page will not be picked by by the validation rules in the KITE script.  This will generate an error and the measurement will fail.',
     'css'=>'global.css',
     'enabled'=>0)
     //'id'=>1)
     );

     $mapper  = new Application_Model_SiteMapper();

     $mapper->save($site);
     //return $this->_helper->redirector('index');
     }

     public function enableAction()
     {
     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender(true);

     $site = new Application_Model_Site(array(
     'user' => $this->_user,
     //'passwd' => '',
     'content'=>'The KITE script is looking for this phrase: Hello, World!.  As long as this is detected then the measurement will complete.',
     'css'=>'global.css',
     'enabled'=>1)
     //'id'=>1)
     );
     $mapper  = new Application_Model_SiteMapper();

     $mapper->save($site);
     //return $this->_helper->redirector('index');
     }

     public function cssAction()
     {
     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender(true);

     $site = new Application_Model_Site(array(
     'user' => $this->_user,
     //'passwd' => '',
     'content'=>'The KITE script is looking for this phrase: Hello, World! but there is now a reference in the page to a CSS file that does not exist.  This will generate a content error.',
     'css'=>'missing.css',
     'enabled'=>2)
     //'id'=>1)
     );
     $mapper  = new Application_Model_SiteMapper();

     $mapper->save($site);
     //return $this->_helper->redirector('index');
     }

     public function timeoutAction()
     {
     $this->_helper->layout->disableLayout();
     $this->_helper->viewRenderer->setNoRender(true);

     $site = new Application_Model_Site(array(
     'user' => $this->_user,
     //'passwd' => '',
     'content'=>'The KITE script is looking for this phrase: Hello, World! but there is now a timeout on the page.  This will generate a Transaction Timed Out error.',
     'css'=>'global.css',
     'enabled'=>3)
     //'id'=>1)
     );
     $mapper  = new Application_Model_SiteMapper();

     $mapper->save($site);
     //return $this->_helper->redirector('index');
     }
     */

}

