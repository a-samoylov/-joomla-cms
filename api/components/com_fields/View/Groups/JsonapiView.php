<?php
/**
 * @package     Joomla.API
 * @subpackage  com_fields
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Component\Fields\Api\View\Groups;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;

/**
 * The groups view
 *
 * @since  4.0.0
 */
class JsonapiView extends BaseApiView
{
	/**
	 * The fields to render item in the documents
	 *
	 * @var  array
	 * @since  4.0.0
	 */
	protected $fieldsToRenderItem = [
		'typeAlias',
		'id',
		'asset_id',
		'context',
		'title',
		'note',
		'description',
		'state',
		'checked_out',
		'checked_out_time',
		'ordering',
		'params',
		'language',
		'created',
		'created_by',
		'modified',
		'modified_by',
		'access',
		'type',
	];

	/**
	 * The fields to render items in the documents
	 *
	 * @var  array
	 * @since  4.0.0
	 */
	protected $fieldsToRenderList = [
		'id',
		'title',
		'name',
		'checked_out',
		'checked_out_time',
		'note',
		'state',
		'access',
		'created_time',
		'created_user_id',
		'ordering',
		'language',
		'fieldparams',
		'params',
		'type',
		'default_value',
		'context',
		'group_id',
		'label',
		'description',
		'required',
		'language_title',
		'language_image',
		'editor',
		'access_level',
		'author_name',
		'group_title',
		'group_access',
		'group_state',
		'group_note'
	];
}
