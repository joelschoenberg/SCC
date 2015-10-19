<?php

class BenchmarkController extends Zend_Controller_Action
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
        $this->_helper->layout->setLayout('benchmark');
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
        $charts = new Application_Model_BenchmarkChartsMapper();
        $result = $charts->fetchAll();

        $this->view->current = $result;
    }

    public function indexAction()
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
        } else {
            $user = $this->_getParam('owner');
        }
        $site = new Application_Model_SiteMapper();

        $result = $site->fetchAll($user);

        if (!$result['chart_id'] | $result['chart_id'] == null) {
          throw new Exception('No Favorite Chart ID found. Please go to settings and enter one.');
        }
        $this->view->key = $result['key'];
        $this->view->secret = $result['secret'];
        $this->view->chartId = $result['chart_id'];
    }

    public function createAction()
    {
        $chart = new Application_Model_BenchmarkCharts(
                array(
                        'cid' => $this->_getParam('id'),
                        'name' => $this->_getParam('name'),
                ));

        $mapper = new Application_Model_BenchmarkChartsMapper();

        $mapper->save($chart);

        $t = new Catchpoint_Pull();
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $myArray[] = $t->fetchData('performance/favoriteCharts/'.$this->_getParam('id').'/data');

        $tests = array();
        foreach ($myArray as $m) {
            foreach ($m->summary->items as $a) {
                $tests[] = array(
                'id' => $chart->cid,
                'name' => $a->breakdown_1->name,
                'dns' => round($a->synthetic_metrics[0] / 1000, 3),
                'wait' => round($a->synthetic_metrics[1] / 1000, 3),
                'load' => round($a->synthetic_metrics[2] / 1000, 3),
                'bytes' => round($a->synthetic_metrics[3] / 1000000, 2),
                'doc_complete' => round($a->synthetic_metrics[4] / 1000, 2),
                'webpage_response' => round($a->synthetic_metrics[5] / 1000, 2),
                'items' => round($a->synthetic_metrics[6], 0), );
            }
        }

        foreach ($tests as $t) {
            $name = preg_replace('/[^A-Za-z0-9\-]/', '', $t['name']);
            $data = new Application_Model_BenchmarkData(
                  array(
                          'chartId' => $t['id'],
                          'name' => $name,
                          'dns' => $t['dns'],
                          'wait' => $t['wait'],
                          'load' => $t['load'],
                          'bytes' => $t['bytes'],
                          'docComplete' => $t['doc_complete'],
                          'webpageResponse' => $t['webpage_response'],
                          'items' => $t['items'],
                  ));

            $mapper = new Application_Model_BenchmarkDataMapper();

            $mapper->save($data);
        }
        $this->_helper->redirector('index', 'benchmark');
    }

    public function testAction()
    {
        if (!$this->_getParam('testid')) {
          throw new Exception('No Test ID found!');
        }
        $site = new Application_Model_SiteMapper();

        $result = $site->fetchAll($this->_user);
        $t = new Catchpoint_Pull();
        $t->override = true;
        $t->key = $result['key'];
        $t->secret = $result['secret'];

        $myArray[] = $t->fetchData('performance/favoriteCharts/'.$result['chart_id'].'/data?tests=' . $this->_getParam('testid'));
        $tests = array();
        foreach ($myArray as $m) {
            foreach ($m->summary->items as $a) {
                $tests[] = array(
              'name' => $a->breakdown_1->name,
              'dns' => round($a->synthetic_metrics[0] / 1000, 3),
              'wait' => round($a->synthetic_metrics[1] / 1000, 3),
              'load' => round($a->synthetic_metrics[2] / 1000, 3),
              'bytes' => round($a->synthetic_metrics[3] / 1000000, 2),
              'doc_complete' => round($a->synthetic_metrics[4] / 1000, 2),
              'webpage_response' => round($a->synthetic_metrics[5] / 1000, 2),
              'items' => round($a->synthetic_metrics[6], 0), );
            }
        }
        $this->session = new Zend_Session_Namespace('Catchpoint');
        $this->session->userChart = $tests[0];

        $this->view->testDetails = $tests[0];
      //$testData = $t->fetchData($request);
    }

    public function viewAction()
    {
        $this->_helper->layout->setLayout('print');

        $data = new Application_Model_BenchmarkDataMapper();

        $result = $data->fetchData($this->_getParam('cid'));

        $data = (array) $result;

        $metrics = array('dns', 'wait', 'load', 'doc_complete', 'webpage_response');
        $metrics2 = array('bytes', 'items');

        $getMetrics = new Catchpoint_Metrics();

        foreach ($metrics as $key => $value) {
            $this->view->$value = $getMetrics->getJson($value, $result);
        }

        foreach ($metrics2 as $key => $value) {
            $this->view->$value = $getMetrics->getJson2($value, $result);
        }

        $this->view->webpageBytes = $getMetrics->getJson3($result);

        $this->session = new Zend_Session_Namespace('Catchpoint');

        $this->view->uname = $this->session->userChart['name'];
        $this->view->udns = $this->session->userChart['dns'];
        $this->view->uwait = $this->session->userChart['wait'];
        $this->view->uload = $this->session->userChart['load'];
        $this->view->udc = $this->session->userChart['doc_complete'];
        $this->view->uwr = $this->session->userChart['webpage_response'];
        $this->view->ubytes = $this->session->userChart['bytes'];
        $this->view->uitems = $this->session->userChart['items'];
    }

    public function addAction()
    {
    }

    public function updateAction()
    {
        //$this->session = new Zend_Session_Namespace('Catchpoint');
        //unset($this->session->token);
        $t = new Catchpoint_Pull();

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $charts = new Application_Model_BenchmarkChartsMapper();
        $result = $charts->fetchAll();

        foreach ($result as $r) {
          $myArray[] = $t->fetchData('performance/favoriteCharts/'.$r->cid.'/data');
          sleep(5);
        }

        $tests = array();
        foreach ($myArray as $m) {
            foreach ($m->summary->items as $a) {
                $tests[] = array(
              'id' => $chart->cid,
              'name' => $a->breakdown_1->name,
              'dns' => round($a->synthetic_metrics[0] / 1000, 3),
              'wait' => round($a->synthetic_metrics[1] / 1000, 3),
              'load' => round($a->synthetic_metrics[2] / 1000, 3),
              'bytes' => round($a->synthetic_metrics[3] / 1000000, 2),
              'doc_complete' => round($a->synthetic_metrics[4] / 1000, 2),
              'webpage_response' => round($a->synthetic_metrics[5] / 1000, 2),
              'items' => round($a->synthetic_metrics[6], 0), );
            }
        }

        foreach ($tests as $t) {
            echo 'Updating '.$t['name'];
            $data = new Application_Model_BenchmarkData(
                array(
                        'chartId' => $t['id'],
                        'name' => $t['name'],
                        'dns' => $t['dns'],
                        'wait' => $t['wait'],
                        'load' => $t['load'],
                        'bytes' => $t['bytes'],
                        'docComplete' => $t['doc_complete'],
                        'webpageResponse' => $t['webpage_response'],
                        'items' => $t['items'],
                ));

            $mapper = new Application_Model_BenchmarkDataMapper();

            $mapper->update($data);
            //echo "Updating " . $t['name'];
            //$this->_helper->redirector('test', 'benchmark');
        }
    }
}
