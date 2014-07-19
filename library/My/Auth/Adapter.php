<?php

class My_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	protected $_user;
	//protected $_pass;

	public function __construct($params)
	{
		$this->_user = $params['username'];
		//$this->_pass = $params['password'];
	}

	public function authenticate()
	{
		/*
		$client = new Zend_Http_Client('https://my.keynote.com/newmykeynote/alarmstatusrssfeed.do');

		$client->setAuth($this->_user, $this->_pass);

		$response = $client->request(Zend_Http_Client::GET);

		if ($response->getMessage() == 'OK') {
		*/
			$mapper    = new Application_Model_SiteMapper();
			$checkUser = $mapper->fetchAll($this->_user);

			if (!$checkUser) {
				$newUser = new Application_Model_Site(array(
					'user'   => $this->_user,
					//'passwd' => $this->_pass,
					'state'  => 'default')
				);

				$result = $mapper->create($newUser);
			}

			return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_user);
			/*
		} else {
			return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->_user);
		}
		*/
	}

}
