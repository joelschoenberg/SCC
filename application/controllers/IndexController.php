<?php
class IndexController extends Zend_Controller_Action {
	protected $_user;
	public function preDispatch() {
		if (! Zend_Auth::getInstance ()->hasIdentity ()) {
			// If the user is logged in, we don't want to show the login form;
			// however, the logout action should still be available
			$this->_helper->redirector ( 'index', 'login' );
		}
	}
	public function init() {
		$this->_user = Zend_Auth::getInstance ()->getIdentity ();
		$this->view->user = $this->_user;
	}
	public function setAction() {
		$state = $this->_getParam ( 'state' );
		
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ( true );
		
		$site = new Application_Model_Site ( array (
				'user' => $this->_user,
				'state' => $state 
		) );
		
		$mapper = new Application_Model_SiteMapper ();
		
		$mapper->save ( $site );
	}
	public function indexAction() {
	}
}

