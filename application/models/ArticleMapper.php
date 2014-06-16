<?php

class Application_Model_ArticleMapper {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Ungültiges Table Data Gateway angegeben');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Article');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Article $article) {

        $data = array(
            'name' => $article->getName(),
            'artnr' => $article->getArtnr(),
            'created' => date('Y-m-d'),
            'price' => $article->getPrice(),
            'active' => $article->getActive(),
            'entry' => $article->getEntry()
        );
        if (null === ($id = $article->getId())) {
            unset($data['id']);
            try {
                $this->getDbTable()->insert($data);
            } catch (Exception $ex) {
                
            }
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function delete(Application_Model_Article $article) {
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $article->getId());
        $this->getDbTable()->delete($where);
    }

    public function find($id, Application_Model_Article $article) {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();

        $article->setId($row->id)
                ->setName($row->name)
                ->setArtnr($row->artnr)
                ->setCreated($row->created)
                ->setEntry($row->entry)
                ->setPrice($row->price)
                ->setActive($row->active);

        return $article;
    }

    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Article();
            $entry->setId($row->id)
                    ->setName($row->name)
                    ->setArtnr($row->artnr)
                    ->setCreated($row->created)
                    ->setEntry($row->entry)
                    ->setPrice($row->price)
                    ->setActive($row->active);
            $entries[] = $entry;
        }
        return $entries;
    }

    public function findArticle($artnr) {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $result = $this->getDbTable()->fetchAll(
                    "artnr = '$artnr'"
            );
        } else {
            $result = $this->getDbTable()->fetchAll(
                    "artnr = '$artnr' AND (active = 1)"
            );
        }
        $row = $result->current();
        $entry = new Application_Model_Article();
        $entry->setId($row->id)
                ->setName($row->name)
                ->setArtnr($row->artnr)
                ->setCreated($row->created)
                ->setEntry($row->entry)
                ->setPrice($row->price)
                ->setActive($row->active);
        return $entry;
    }

}
