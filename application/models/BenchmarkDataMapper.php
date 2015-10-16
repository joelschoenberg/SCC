<?php

class Application_Model_BenchmarkDataMapper
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
            $this->setDbTable('Application_Model_DbTable_BenchmarkData');
        }

        return $this->_dbTable;
    }

    public function save(Application_Model_BenchmarkData $benchmark)
    {
        $data = array(
                'chart_id' => $benchmark->getChartId(),
                'name' => $benchmark->getName(),
                'dns' => $benchmark->getDns(),
                'wait' => $benchmark->getWait(),
                'load' => $benchmark->getLoad(),
                'bytes' => $benchmark->getBytes(),
                'doc_complete' => $benchmark->getDocComplete(),
                'webpage_response' => $benchmark->getWebpageResponse(),
                'items' => $benchmark->getItems(),
        );

        $this->getDbTable()->insert($data);
    }

    public function update(Application_Model_BenchmarkData $benchmark)
    {
        $data = array(
                'chart_id' => $benchmark->getChartId(),
                'name' => $benchmark->getName(),
                'dns' => $benchmark->getDns(),
                'wait' => $benchmark->getWait(),
                'load' => $benchmark->getLoad(),
                'bytes' => $benchmark->getBytes(),
                'doc_complete' => $benchmark->getDocComplete(),
                'webpage_response' => $benchmark->getWebpageResponse(),
                'items' => $benchmark->getItems(),
        );

        $this->getDbTable()->update($data, array('chart_id = ?' => $benchmark->getChartId(), 'name' => $benchmark->getName()));
    }

    public function find($id, Application_Model_BenchmarkData $benchmark)
    {
        $result = $this->getDbTable()->find($id);

        if (0 == count($result)) {
            return;
        }
        $row = $result->current();

        $benchmark->setId($row->id)
                  ->setChartId($row->chart_id)
                  ->setName($row->name)
                  ->setDns($row->dns)
                  ->setWait($row->wait)
                  ->setLoad($row->load)
                  ->setBytes($row->bytes)
                  ->setDocComplete($row->doc_complete)
                  ->setWebpageResponse($row->webpage_response)
                  ->setItems($row->items);
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $charts = array();
        foreach ($resultSet as $row) {
            $chart = new Application_Model_Benchmark();
            $chart->setId($row->id)
                ->setChartId($row->chart_id)
                ->setName($row->name)
                ->setDns($row->dns)
                ->setWait($row->wait)
                ->setLoad($row->load)
                ->setBytes($row->bytes)
                ->setDocComplete($row->doc_complete)
                ->setWebpageResponse($row->webpage_response)
                ->setItems($row->items);
            $charts[] = $chart;
        }

        return $charts;
    }

    public function fetchData($chartId)
    {
        $query = $this->getDbTable()
        ->select()
        ->where('chart_id = ?', $chartId);
        $resultSet = $this->getDbTable()->fetchAll($query);

        return $resultSet;
    }
}
