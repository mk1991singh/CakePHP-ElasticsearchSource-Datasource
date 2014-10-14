<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: Nov 18, 2013
 * Time: 12:58:16 PM
 * Format: http://book.cakephp.org/2.0/en/models.html
 */
App::uses('HttpSourceModel', 'HttpSource.Model');

/**
 * ElasticsearchModel Model
 * 
 * @package ElasticsearchSource
 * @subpackage Indices
 */
class ElasticsearchModel extends HttpSourceModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'ElasticsearchModel';

	/**
	 * Elasticsearch index
	 *
	 * @var string
	 */
	public $useIndex;

	/**
	 * Elasticsearch index type
	 *
	 * @var string
	 */
	public $useType;

	/**
	 * Analysis language
	 *
	 * @var string
	 */
	public $language;

	/**
	 * Analysis implementation
	 *
	 * @var string
	 */
	public $implementation;

	/**
	 * Document scheme
	 *
	 * @var string
	 */
	public $scheme;

	/**
	 * opType elasticsearch parameter
	 *
	 * @var string
	 */
	public $opType;

	/**
	 * {@inheritdoc}
	 * With index/type support
	 * 
	 * @param string $tableName
	 * @param string $indexName
	 * @param string $typeName
	 * @param string $language
	 * @param string $implementation
	 * @throws MissingTableException when database table $tableName is not found on data source
	 */
	public function setSource($tableName, $indexName = null, $typeName = null, $language = null, $implementation = null, $scheme = null, $opType = null) {
		if ($indexName) {
			$this->useIndex = $indexName;
		}
		if (!is_null($typeName)) {
			$this->useType = $typeName;
		}
		if (!is_null($language)) {
			$this->language = $language;
		}
		if (!is_null($implementation)) {
			$this->implementation = $implementation;
		}
		if (!is_null($scheme)) {
			$this->scheme = $scheme;
		}
		if (!is_null($opType)) {
			$this->opType = $opType;
		}
		parent::setSource($tableName);
	}

	/**
	 * {@inheritdoc}
	 * With index/type support
	 * 
	 * @param array $queryData
	 * @return array
	 */
	public function beforeFind($queryData) {
		$queryData = Hash::insert($queryData, 'conditions.index', $this->useIndex);
		$queryData = Hash::insert($queryData, 'conditions.type', $this->useType);
		return $queryData;
	}

	/**
	 * {@inheritdoc}
	 * With index/type support
	 * 
	 * @param array $options
	 * @return bool
	 */
	public function beforeSave($options = array()) {
		if ($this->opType == 'create' ) {
			$this->data[$this->alias]['op_type'] = $this->opType;
		}
		$this->set('language', $this->language);
		$this->set('implementation', $this->implementation);
		$this->set('index', $this->useIndex);
		$this->set('type', $this->useType);
		$this->set('scheme', $this->scheme);
		return parent::beforeSave($options);
	}

	/**
	 * {@inheritdoc}
	 * With index/type support
	 * 
	 * @param integer|string $id ID of record to delete
	 * @param boolean $cascade Set to true to delete records that depend on this record
	 * @return boolean True on success
	 */
	public function delete($id = null, $cascade = true) {
		return $this->deleteAll(array(
					$this->primaryKey => $id,
					'index' => $this->useIndex,
					'type' => $this->useType
						), $cascade);
	}

}
