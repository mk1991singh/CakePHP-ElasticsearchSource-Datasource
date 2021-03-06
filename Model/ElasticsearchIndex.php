<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 12.12.2014
 * Time: 11:03:22
 * Format: http://book.cakephp.org/2.0/en/models.html
 */
App::uses('ElasticsearchModel', 'ElasticsearchSource.Model');

/**
 * Indices Status
 *
 * @link http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/indices.html
 * @package ElasticsearchSource
 * @subpackage Model
 */
class ElasticsearchIndex extends ElasticsearchModel {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $name = 'ElasticsearchIndex';

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $useTable = 'index';

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	public $primaryKey = 'name';

}
