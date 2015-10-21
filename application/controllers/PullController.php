<?php

class PullController extends Zend_Controller_Action
{
    protected $_user;

    protected $_tokenUrl = 'https://io.catchpoint.com/ui/api/';

    protected $_url = 'https://io.catchpoint.com/ui/api/v1/';

    protected $_key;

    protected $_secret;

    protected $_token;

    protected $_expires;

    protected $_session;

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
        $this->_helper->layout->setLayout('basic');

        $this->_session = new Zend_Session_Namespace('Catchpoint');

        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;
        $user = new Application_Model_SiteMapper();

        $result = $user->fetchAll($this->_user);

        if ($result->key == null) {
            throw new Exception('No Key/Secret set!');
        } else {
            $this->_key = base64_decode($result->key);
            $this->_secret = base64_decode($result->secret);

            if (!isset($this->_session->token) || $this->_session->token == '') {
                $this->getToken();
            }
        }
    }

    public function indexAction()
    {
    }

    private function getToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_tokenUrl.'token');
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                'grant_type=client_credentials&client_id='.$this->_key.
                '&client_secret='.$this->_secret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /*
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'w+'));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        */

        $result = curl_exec($ch);

        curl_close($ch);

        $jsonResponse = json_decode($result);

        if ($jsonResponse->Message) {
            throw new Exception($jsonResponse->Message);
        } else {
            $this->_token = $jsonResponse->access_token;
            $this->_expires = $jsonResponse->expires_in;
            $this->_session->token = base64_encode($this->_token);
            $this->_session->setExpirationSeconds($this->_expires);
        }
    }

    public function apiAction()
    {
        echo '<pre>';
        $request = $this->_getParam('call');
        $ch = curl_init();

        echo $this->_url.$request."\r\n";
        curl_setopt($ch, CURLOPT_URL, $this->_url.$request);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
                array(
                        'Authorization: Bearer '.$this->_session->token,
                ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'w+'));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        $result = curl_exec($ch);
        curl_close($ch);

        if (isset($result->Message)) {
          throw new Exception($result->Message);
        }

        print_r(json_decode($result));
        echo '</pre>';
    }
}
