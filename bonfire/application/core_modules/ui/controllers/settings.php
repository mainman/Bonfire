<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Bonfire.UI.Manage');
		$this->lang->load('ui');

		Template::set('toolbar_title', 'UI Settings');

		Assets::add_module_js('ui', 'ui.js');
	}

	//--------------------------------------------------------------------

	public function index()
	{
		if ($this->input->post('add_shortcut'))
		{
			if ($this->add())
			{
				Template::set_message('Your shortcut was successfully added.', 'success');
				redirect(uri_string());
			}
			else
			{
				Template::set_message('There was an error saving your shortcuts.', 'error');
			}
		}
		elseif ($this->input->post('remove_action'))
		{
			if ($this->remove())
			{
				Template::set_message('Your shortcut was successfully removed.', 'success');
				redirect(uri_string());
			}
			else
			{
				Template::set_message('There was an error removing your shortcut.', 'error');
			}
		}
		elseif ($this->input->post('submit'))
		{
			if ($this->save_settings())
			{
				Template::set_message('Your shortcuts were successfully saved.', 'success');
				redirect(uri_string());
			}
			else
			{
				Template::set_message('There was an error saving your shortcuts.', 'error');
			}
		}

		// Read our current settings from the application config
		Template::set('current', config_item('ui.current_shortcuts'));

		$settings = $this->settings_lib->item('ui.shortcut_keys');
		Template::set('settings', unserialize($settings));

		Template::render();
	}

	//--------------------------------------------------------------------

	// --------------------------------------------------------------------

	/*
		Method: add()

		Parameter:
			$role_perm	- A CSV string of the role and the permission to modify
			$action		- boolean ()True = Insert, False = Delete)

		Return:
			string result
	*/

	public function add()
	{

		$this->form_validation->set_rules('action1', lang('ui_actions'), 'required|xss_clean');
		$this->form_validation->set_rules('shortcut1', lang('ui_shortcuts'), 'required|callback_validate_shortcuts|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		$action   = $this->input->post('action1');
		$shortcut = $this->input->post('shortcut1');

		// Read our current settings from the application config
		$available_actions = config_item('ui.current_shortcuts');
		$current_settings = unserialize($this->settings_lib->item('ui.shortcut_keys'));

		if (array_key_exists($action, $available_actions))
		{
			if (!array_key_exists($action, $current_settings)) {
				$current_settings[$action] = $shortcut;

				return $this->save_settings($current_settings);
			}
		}
		return false;
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	public function remove()
	{
		$this->form_validation->set_rules('remove_action', lang('ui_actions'), 'required|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		$action   = $this->input->post('remove_action');

		// Read our current settings from the application config
		$current_settings = unserialize($this->settings_lib->item('ui.shortcut_keys'));

		if (array_key_exists($action, $current_settings)) {
			unset($current_settings[$action]);

			return $this->save_settings($current_settings);
		}
		return false;
	}

	//--------------------------------------------------------------------

	private function save_settings($settings = array())
	{
		if (empty($settings))
		{
			$actions = $this->input->post('action');
			$shortcuts = $this->input->post('shortcut');

			if (is_array($actions) && !empty($actions) && is_array($shortcuts) && !empty($shortcuts))
			{
				foreach ($actions as $num => $value)
				{
					$this->form_validation->set_rules('action['.$num.']', lang('ui_actions'), 'required|xss_clean');
					$this->form_validation->set_rules('shortcut['.$num.']', lang('ui_shortcuts'), 'required|callback_validate_shortcuts|xss_clean');

					$settings[$value] = $shortcuts[$num];
				}

				if ($this->form_validation->run() === false)
				{
					return false;
				}
			}

		}

		$updated = $this->settings_lib->set('ui.shortcut_keys', serialize($settings));

		// Log the activity
		$this->load->model('activities/Activity_model', 'activity_model');

		$this->activity_model->log_activity($this->current_user->id, lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'ui');

		return $updated;
	}

	//--------------------------------------------------------------------

	public function validate_shortcuts($shortcut)
	{
		// Make sure that the shortcuts don't have spaces

		if (stristr($shortcut, " ") !== FALSE)
		{
			$this->form_validation->set_message('validate_shortcuts', lang('ui_shortcut_error'));
			return FALSE;
		}

		return TRUE;

	}

}