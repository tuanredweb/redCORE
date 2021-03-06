<?php
/**
 * @package     Redcore.Backend
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2012 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Product View
 *
 * @package     Redcore.Backend
 * @subpackage  Views
 * @since       1.0
 */
class RedcoreViewConfig extends RedcoreHelpersView
{
	/**
	 * @var  JForm
	 */
	protected $form;

	/**
	 * @var  string
	 */
	protected $component;

	/**
	 * @var  string
	 */
	protected $return;

	/**
	 * @var  array
	 */
	protected $components;

	/**
	 * @var  array
	 */
	protected $modules;

	/**
	 * @var  array
	 */
	protected $plugins;

	/**
	 * @var  array
	 */
	protected $contentElements;

	/**
	 * @var  array
	 */
	protected $missingContentElements;

	/**
	 * Display method
	 *
	 * @param   string  $tpl  The template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		/** @var RedcoreModelConfig $model */
		$model = $this->getModel('Config');

		$this->form	= $model->getForm();
		$this->component = $model->getComponent();
		$this->return = JFactory::getApplication()->input->get('return');
		$option = JFactory::getApplication()->input->getString('component', '');

		$this->modules = $model->getInstalledExtensions('module', array('%' . $this->component->xml->xmlComponentName . '%'));
		$this->plugins = $model->getInstalledExtensions('plugin', array('%' . $this->component->xml->xmlComponentName . '%'), $this->component->xml->xmlComponentName);
		$this->componentTitle = RText::getTranslationIfExists($this->component->xml->name, '', '');
		$this->contentElements = $model->loadContentElements($option);
		$this->missingContentElements = $model->loadMissingContentElements($option, $this->contentElements);
		RLayoutHelper::$defaultBasePath = JPATH_ADMINISTRATOR . '/components/' . $option . '/layouts';

		parent::display($tpl);
	}

	/**
	 * Get the view title.
	 *
	 * @return  string  The view title.
	 */
	public function getTitle()
	{
		return $this->componentTitle . ' ' . JText::_('COM_REDCORE_CONFIG_FORM_TITLE');
	}

	/**
	 * Get the toolbar to render.
	 *
	 * @return  RToolbar
	 */
	public function getToolbar()
	{
		$group = new RToolbarButtonGroup;
		$secondGroup = new RToolbarButtonGroup;
		$user = JFactory::getUser();

		if ($user->authorise('core.admin', 'com_redcore'))
		{
			$save = RToolbarBuilder::createSaveButton('config.apply');
			$saveAndClose = RToolbarBuilder::createSaveAndCloseButton('config.save');

			$group->addButton($save)
				->addButton($saveAndClose);
		}

		$cancel = RToolbarBuilder::createCloseButton('config.cancel');

		$group->addButton($cancel);

		$toolbar = new RToolbar;
		$toolbar->addGroup($group)
			->addGroup($secondGroup);

		return $toolbar;
	}
}
