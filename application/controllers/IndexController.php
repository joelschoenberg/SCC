<?php

class IndexController extends Zend_Controller_Action
{
    protected $_user;

    protected $_state;

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

        $site = new Application_Model_SiteMapper();

        $result = $site->fetchAll($this->_user);
        $this->view->user = $result->user;
        $this->_state = $result->state;
        $this->view->state = $result->state;
        $this->view->key = (empty($result->key)) ? '' : '.....';
        $this->view->secret = (empty($result->secret)) ? '' : '..................';
        $this->view->chartId = (empty($result->chart_id)) ? '' : $result->chart_id;

        $siteOptions = array(
                'default' => array('flash', 'Default'),
                'validation_error' => array('warning-sign', 'Validation Error'),
                'css_error' => array('warning-sign', 'CSS Error'),
                'js_error' => array('warning-sign', 'JavsScript Error'),
                '404' => array('exclamation-sign', '40x Error'),
                '500' => array('ban-circle', '500x Error'),
                'delay' => array('pause', 'Content Delay'),
                'timeout' => array('time', 'Timeout'),
                'cookies' => array('warning-sign', 'Cookies'),
                'hosts_and_zones' => array('filter', 'Hosts &amp; Zones'),
                'chrome' => array('globe', 'Chrome'),
                'glimpse' => array('user', 'Glimpse')
        );

        $this->view->menu = $siteOptions;
    }

    public function setAction()
    {
        $this->state = $this->_getParam('state');

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $site = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'state' => $this->state,
                ));

        $mapper = new Application_Model_SiteMapper();

        $mapper->save($site);
    }

    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->renderScript('site/'.$this->_state.'.phtml');
    }

    public function stateAction()
    {
        $state = $this->_getParam('type');
        $this->renderScript('site/'.$state.'.phtml');
        $site = new Application_Model_Site(
                array(
                        'user' => $this->_user,
                        'state' => $state,
                ));

        $mapper = new Application_Model_SiteMapper();

        $mapper->save($site);

        $this->_helper->redirector('index', 'index');

    }
}
