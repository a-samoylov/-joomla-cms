<?php
/**
 * @package     Joomla.API
 * @subpackage  com_plugins
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Plugins\Api\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\String\Inflector;
use Joomla\CMS\Router\Exception\RouteNotFoundException;
use Joomla\CMS\MVC\Controller\Exception\ResourceNotFound;
use Tobscure\JsonApi\Exception\InvalidParameterException;

/**
 * The plugins controller
 *
 * @since  4.0.0
 */
class PluginsController extends ApiController
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $contentType = 'plugins';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $default_view = 'plugins';

	/**
	 * Basic display of an item view
	 *
	 * @param   integer  $id  The primary key to display. Leave empty if you want to retrieve data from the request
	 *
	 * @return  static  A \JControllerLegacy object to support chaining.
	 *
	 * @since   4.0.0
	 */
	public function displayItem($id = null)
	{
		if ($id === null)
		{
			$id = $this->input->get('id', 0, 'int');
		}

		$this->input->set('view', Inflector::singularize($this->default_view));

		return parent::displayItem($id);
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @return  boolean  True if save succeeded after access level check and checkout passes, false otherwise.
	 *
	 * @since   4.0.0
	 */
	public function edit()
	{
		$recordId = $this->input->getInt('id');

		if (!$recordId)
		{
			// TODO: Lang string for exception
			throw new ResourceNotFound('Record does not exist', 404);
		}

		$data = json_decode($this->input->json->getRaw(), true);

		foreach ($data as $key => $value)
		{
			if (!in_array($key, ['enabled', 'access', 'ordering']))
			{
				throw new InvalidParameterException("Invalid parameter {$key}.", 400);
			}
		}

		/** @var \Joomla\Component\Plugins\Administrator\Model\PluginModel $model */
		$model = $this->getModel(Inflector::singularize($this->contentType), '', ['ignore_request' => true]);

		if (!$model)
		{
			throw new \RuntimeException('Unable to create the model');
		}

		try
		{
			$modelName = $model->getName();
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException('Internal server error', 500, $e);
		}

		$model->setState($modelName . '.id', $recordId);

		$item = $model->getItem();

		if (!isset($item->extension_id))
		{
			throw new RouteNotFoundException('Item does not exist');
		}

		$data['folder']  = $item->folder;
		$data['element'] = $item->element;

		$this->input->set('data', $data);

		return parent::edit();
	}
}
