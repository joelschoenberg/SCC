<?php

class FeedController extends Zend_Controller_Action
{

    protected $_user;
    protected $_passwd;

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
        } else {
            throw new Exception('Not logged in!');
        }

        $site = new Application_Model_SiteMapper();

        $result = $site->fetchAll($user);

        $this->_user   = $result->user;
        //$this->_passwd = $result->passwd;
    }

    public function indexAction()
    {
    	/*
        $client = new Zend_Http_Client('https://my.keynote.com/newmykeynote/alarmstatusrssfeed.do');
        

        $client->setAuth($this->_user, $this->_passwd);

        $response = $client->request(Zend_Http_Client::GET);

        if ($response->getMessage() == 'OK') {
            //$this->view->xml = simplexml_load_string($response->getBody());
            $xml = Zend_Feed::importString($response->getBody());
            foreach ($xml as $item) {
                $items[] = array(
                    'pubDate' => $item->pubDate(),
                    'title'   => $item->title(),
                    'link'    => $item->link(),
                    'desc'	  => explode('||', $item->description())
                );

            }
            if (isset($items)) {
                arsort($items);
                $this->view->xml = $items;
            } else {
                $this->_helper->viewRenderer->setNoRender(true);
                echo 'No RSS feed currently available!';
            }
        } else {
            throw new Exception('Error getting feed');
        }
        */
    }

}