<?php

class Application_Model_BenchmarkChartsMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;

        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_BenchmarkCharts');
        }

        return $this->_dbTable;
    }

    public function save(Application_Model_BenchmarkCharts $chart)
    {
        $data = array(
                'cid' => $chart->getCid(),
                'name' => $chart->getName(),
        );

        if (null === ($id = $chart->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_BenchmarkCharts $chart)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $chart->setId($row->id)
                  ->setCid($row->cid)
                  ->setName($row->name);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $charts = array();
        foreach ($resultSet as $row) {
            $chart = new Application_Model_BenchmarkCharts();
            $chart->setId($row->id)
                ->setCid($row->cid)
                ->setName($row->name);
            $charts[] = $chart;
        }

        return $charts;
    }
}
