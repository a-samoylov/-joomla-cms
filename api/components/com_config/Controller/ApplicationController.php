<?php
/**
 * @package     Joomla.API
 * @subpackage  com_plugins
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Config\Api\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Controller\ApiController;
use Joomla\Component\Config\Api\View\Application\JsonApiView;
use Joomla\Component\Config\Administrator\Model\ApplicationModel;

/**
 * The application controller
 *
 * @since  4.0.0
 */
class ApplicationController extends ApiController
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $contentType = 'application';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $default_view = 'application';

	/**
	 * Basic display of a list view
	 *
	 * @return  static  A \JControllerLegacy object to support chaining.
	 *
	 * @since   4.0.0
	 */
	public function displayList()
	{
		$viewType = $this->app->getDocument()->getType();
		$viewLayout = $this->input->get('layout', 'default', 'string');

		try
		{
			/** @var JsonApiView $view */
			$view = $this->getView($this->default_view, $viewType, '', ['base_path' => $this->basePath, 'layout' => $viewLayout, 'contentType' => $this->contentType]);
		}
		catch (\Exception $e)
		{
			return $this;
		}

		/** @var ApplicationModel $model */
		$model = $this->getModel($this->contentType);

		if (!$model)
		{
			throw new \RuntimeException('Model failed to be created', 500);
		}

		// Push the model into the view (as default)
		$view->setModel($model, true);

		$view->document = $this->app->getDocument();
		$view->displayList();

		return $this;
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
		/** @var ApplicationModel $model */
		$model = $this->getModel($this->contentType);

		if (!$model)
		{
			throw new \RuntimeException('Model failed to be created', 500);
		}

		// Access check.
		if (!$this->allowEdit())
		{
			throw new NotAllowed('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED', 403);
		}

		$data = json_decode($this->input->json->getRaw(), true);

		// Complete data array if needed
		$oldData = $model->getData();
		$data = array_replace($oldData, $data);

		// TODO: Not the cleanest thing ever but it works...
		Form::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . '/forms');

		// Must load after serving service-requests
		$form = $model->getForm();

		// Validate the posted data.
		$return = $model->validate($form, $data);

		if ($return === false)
		{
			throw new \RuntimeException('Invalid input data', 400);
		}

		$data   = $return;
		$return = $model->save($data);

		if ($return === false)
		{
			throw new \RuntimeException('Internal server error', 500);
		}

		return true;
	}
}
