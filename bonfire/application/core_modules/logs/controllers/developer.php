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

/*
	Class: Logs Developer Context

	Allows the developer to view the logs that have been generated by the system.
*/
class Developer extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Developer.View');
		$this->auth->restrict('Bonfire.Logs.View');

		$this->lang->load('logs');
		Template::set('toolbar_title', lang('log_title'));

		Template::set_block('sub_nav', 'developer/_sub_nav');

		// Logging enabled?
		Template::set('log_threshold', $this->config->item('log_threshold'));
	}

	//--------------------------------------------------------------------

	/*
		Method: index()

		Lists all log files and allows you to change the log_threshold.
	*/
	public function index()
	{
		$this->load->helper('file');

		// Are we doing bulk actions?
		if ($action = $this->input->post('submit'))
		{
			if ($action == lang('bf_action_delete') && has_permission('Bonfire.Logs.Manage'))
			{
				$checked = $this->input->post('checked');

				if (is_array($checked) && count($checked))
				{
					$this->load->model('activities/Activity_model', 'activity_model');

					foreach ($checked as $file)
					{
						@unlink($this->config->item('log_path') . $file);
						$activity_text = 'log file '.date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $file))));
						$this->activity_model->log_activity($this->current_user->id, ucfirst($activity_text) . ' deleted from: ' . $this->input->ip_address(), 'logs');
					}

					Template::set_message(count($checked) .' '. lang('log_deleted'), 'success');
				}
			}
		}

		// Load the Log Files
		$logs = array_reverse(get_filenames($this->config->item('log_path')));

		// Pagination
		$this->load->library('pagination');

		$offset = $this->uri->segment(5) ? $this->uri->segment(5) : 0;
		//$limit = $this->limit;
		$limit = 10;

		$this->pager['base_url'] = site_url(SITE_AREA .'/developer/logs/index');
		$this->pager['total_rows'] = count($logs);
		$this->pager['per_page'] = $limit;
		$this->pager['uri_segment']	= 5;

		$this->pagination->initialize($this->pager);

		Template::set('logs', array_slice($logs, $offset, $limit));

		Template::render();
	}

	//--------------------------------------------------------------------

	public function settings()
	{
		$this->auth->restrict('Bonfire.Logs.Manage');

		Template::set('toolbar_title', lang('log_title_settings'));

		Template::render();
	}

	//--------------------------------------------------------------------

	/*
		Method: enable()

		Saves the logging threshold value.
	*/
	public function enable()
	{
		$this->auth->restrict('Bonfire.Logs.Manage');

		if ($this->input->post('submit'))
		{
			$this->load->helper('config_file');

			if (write_config('config', array('log_threshold' => $_POST['log_threshold'])))
			{
				$this->load->model('activities/Activity_model', 'activity_model');

				// Log the activity
				$this->activity_model->log_activity( intval ( $this->current_user->id ), 'Log settings modified from: ' . $this->input->ip_address(), 'logs');

				Template::set_message('Log settings successfully saved.', 'success');
			} else
			{
				Template::set_message('Unable to save log settings. Check the write permissions on <b>application/config.php</b> and try again.', 'error');
			}
		}

		redirect(SITE_AREA .'/developer/logs');
	}

	//--------------------------------------------------------------------

	/*
		Method: view()

		Shows the contents of a single log file.

		Parameter:
			$file	- the full name of the file to view (including extension).
	*/
	public function view($file='')
	{
		if (empty($file))
		{
			$file = $this->uri->segment(4);
		}

		if (empty($file))
		{
			Template::set_message('No log file provided.', 'error');
			Template::redirect(SITE_AREA .'/developer/logs');
		}

		Assets::add_module_js('logs', 'logs');

		Template::set('log_file', $file);
		Template::set('log_file_pretty', date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $file)))));
		Template::set('log_content', file($this->config->item('log_path') . $file));
		Template::render();
	}

	//--------------------------------------------------------------------

	/*
		Method: purge()

		Deletes all existing log files.
	*/
	public function purge()
	{
		$this->auth->restrict('Bonfire.Logs.Manage');

		$this->load->helper('file');

		$file = $this->uri->segment(5);

		if ($file)
		{
			@unlink($this->config->item('log_path') . $file);
			$activity_text = 'log file '.date('F j, Y', strtotime(str_replace('.php', '', str_replace('log-', '', $file))));
		}
		else
		{
			delete_files($this->config->item('log_path'));
			$activity_text = "all log files";
			// restore the index.html file
			@copy(APPPATH.'/index.html',$this->config->item('log_path').'/index.html');
		}

		// since the $activity_text is being repurposed here, lowercase the first letter of the sentence to fit this sentence
		Template::set_message("Successfully purged " . $activity_text,'success');

		// Log the activity
		$this->load->model('activities/Activity_model', 'activity_model');


		$this->activity_model->log_activity( intval ($this->current_user->id ), ucfirst($activity_text) . ' purged from: ' . $this->input->ip_address(), 'logs');

		redirect(SITE_AREA .'/developer/logs');
	}

	//--------------------------------------------------------------------

}
