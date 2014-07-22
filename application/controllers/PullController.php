<?php
class PullController extends Zend_Controller_Action {
	protected $_user;
	protected $_key = 'RY-Pw-jRXRWW9PsE';
	protected $_secret = '16409a47-ac89-42f1-811c-0fe666ab4138';
	protected $_token;
	protected $_expires;
	public $session;
	public function preDispatch() {
		if (! Zend_Auth::getInstance ()->hasIdentity ()) {
			// If the user is logged in, we don't want to show the login form;
			// however, the logout action should still be available
			$this->_helper->redirector ( 'index', 'login' );
		}
	}
	public function init() {
		$this->session = new Zend_Session_Namespace ( 'Catchpoint' );
		
		$this->_user = Zend_Auth::getInstance ()->getIdentity ();
		$this->view->user = $this->_user;
		
		$this->_helper->layout->setLayout ( 'basic' );
		
		$site = new Application_Model_SiteMapper ();
		
		$result = $site->fetchAll ( $this->_user );
		
		if ($result->key == null) {
			$this->view->settings = 'please enter your key';
		} else {
			
			$this->_key = $result->key;
			$this->_secret = $result->secret;
			
			if (! isset ( $this->session->token )) {
				$this->getToken ();
				$this->session->token = base64_encode ( $this->_token );
				$this->session->setExpirationSeconds ( $this->_expires );
			}
		}
		
	}
	public function indexAction() {
	}
	private function getToken() {
		$ch = curl_init ();
		
		curl_setopt ( $ch, CURLOPT_URL, 'https://io.catchpoint.com/ui/api/token' );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&client_id=' . $this->_key . '&client_secret=' . $this->_secret );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_VERBOSE, true );
		
		$result = curl_exec ( $ch );
		
		curl_close ( $ch );
		
		$jsonResponse = json_decode ( $result );
		
		$this->_token = $jsonResponse->access_token;
		$this->_expires = $jsonResponse->expires_in;
	}
	public function nodesAction() {
		$ch = curl_init ();
		
		curl_setopt ( $ch, CURLOPT_URL, 'https://io.catchpoint.com/ui/api/v1/nodes' );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Authorization: Bearer ' . $this->session->token 
		) );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$nodes = curl_exec ( $ch ) ;
		
		curl_close ( $ch );
		$this->view->nodes = $nodes;
	}

}

