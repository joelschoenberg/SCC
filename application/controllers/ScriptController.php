<?php

class ScriptController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->disableLayout();
    }

    public function viewAction()
    {
        $this->view->state = 'default';
        $hrs  = date('G');
        $mins = date('i');
        $randomMinute = rand(0,60);



        if ($hrs >= 6 && $hrs <= 7) {
            $this->_helper->viewRenderer->setNoRender(true);
            header("HTTP/1.1 500 Application Error");
            $this->view->state = '500';
        } else {
            if ($mins == $randomMinute + 2) {
                $this->_helper->viewRenderer->setNoRender(true);
                header("HTTP/1.1 500 Application Error");
                $this->view->state = '500';
            }

            if ($mins == $randomMinute + 4) {
                $this->view->state = 'css_error';
            }

            if ($mins == $randomMinute + 6) {
                $this->_helper->viewRenderer->setNoRender(true);
                header("HTTP/1.1 404 Not Found");
                $this->view->state = '404';
            }

            if ($mins == $randomMinute + 8) {
                $this->view->state = 'timeout';
            }

            if ($hrs >= 15 && $hrs <= 17) {
                //echo "Time is between 15 and 16hrs";
                $this->view->state = 'delay';
            }
        }
    }
}

