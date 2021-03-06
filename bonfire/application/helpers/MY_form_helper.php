<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	File: MY_form_helper

	Creates HTML5 extensions for the standard CodeIgniter form helper.

	These functions also wrap the form elements as necessary to create
	the styling that the Bootstrap-inspired admin theme requires to
	make it as simple as possible for a developer to maintain styling
	with the core. Also makes changing the core a snap.

	All methods (including overriden versions of the originals) now
	support passing a final 'label' attribute that will create the
	label along with the field.
*/

/*
	Function: _form_common()

	Used by many of the new functions to wrap the input in the correct
	tags so that the styling is automatic.

	Parameters:
		$type	- A string with the name of the element type.
		$data	- Either a string with the element name, or an array of
				  key/value pairs of all attributes.
		$value	- Either a string with the value, or blank if an array is
				  is passed to the $data param.
		$label	- A string with the label of the element.
		$extra	- A string with any additional items to include, like Javascript.
		$tooltip - A string for inline help or a tooltip icon

	Returns:
		A string with the formatted input element, label tag and wrapping divs.
*/
if (!function_exists('_form_common'))
{
	function _form_common($type='text', $data='', $value='', $label='', $extra='', $tooltip = '')
	{
		$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);

		// If name is empty at this point, try to grab it from the $data array
		if (empty($defaults['name']) && is_array($data) && isset($data['name']))
		{
			$defaults['name'] = $data['name'];
			unset($data['name']);
		}
		$output = _parse_form_attributes($data, $defaults);
		$output = <<<EOL

	<div class="control-group">
		<label class="control-label" for="{$defaults['name']}">{$label}</label>
		<div class="controls">
			 <input {$output} {$extra} />
			{$tooltip}
		</div>
	</div>

EOL;

		return $output;
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_input'))
{
		/*
		 Function: form_input()

		 Returns a properly templated text input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_input($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('text', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_email'))
{
	/*
		 Function: form_email()

		 Returns a properly templated email input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_email($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('email', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_password'))
{
	/*
		 Function: form_password()

		 Returns a properly templated password input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_password($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('password', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if (!function_exists('form_url'))
{
	/*
		 Function: form_url()

		 Returns a properly templated URL input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	 */
	function form_url($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('url', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_telephone'))
{
	 /*
		 Function: form_telephone()

		 Returns a properly templated Telephone input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	  */
		function form_telephone($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('tel', $data, $value, $label, $extra, $tooltip);
		}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_number'))
{
	/*
		 Function: form_number()

		 Returns a properly templated number input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
		*/
		function form_number($data='', $value='', $label='', $extra='', $tooltip = '' )
		{
				return _form_common('number', $data, $value, $label, $extra, $tooltip);
		}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_color'))
{
	/*
		 Function: form_color()

		 Returns a properly templated color input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	*/
	function form_color($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('color', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_search'))
{
	/*
		 Function: form_search()

		 Returns a properly templated search input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	*/
	function form_search($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('search', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_date'))
{
	/*
		 Function: form_date()

		 Returns a properly templated date input field.

		 Parameters:
			 $data	- Either a string with the element name, or an array of
						 key/value pairs of all attributes.
			 $value	- Either a string with the value, or blank if an array is
						 is passed to the $data param.
			 $label	- A string with the label of the element.
			 $extra	- A string with any additional items to include, like Javascript.
			 $tooltip - A string for inline help or a tooltip icon

		 Returns:
			 A string with the formatted input element, label tag and wrapping divs.
	*/
	function form_date($data='', $value='', $label='', $extra='', $tooltip = '' )
	{
		return _form_common('date', $data, $value, $label, $extra, $tooltip);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('form_dropdown'))
{
		/*
			 Function: form_dropdown()

			 Returns a properly templated date input field.

			 Parameters:
				 $data	- Either a string with the element name, or an array of
							 key/value pairs of all attributes.
				 $value	- Either a string with the value, or blank if an array is
							 is passed to the $data param.
				 $label	- A string with the label of the element.
				 $extra	- A string with any additional items to include, like Javascript.
				 $tooltip - A string for inline help or a tooltip icon

			 Returns:
				 A string with the formatted input element, label tag and wrapping divs.
		*/
		function form_dropdown($data, $options='', $selected='', $label='', $extra='', $tooltip = '' )
		{
			$defaults = array('name' => (( ! is_array($data)) ? $data : ''));

			// If name is empty at this point, try to grab it from the $data array
			if (empty($defaults['name']) && is_array($data) && isset($data['name']))
			{
				$defaults['name'] = $data['name'];
				unset($data['name']);
			}
			$output = _parse_form_attributes($data, $defaults);
			
			if ( ! is_array($selected))
			{
				$selected = array($selected);
			}

			// If no selected state was submitted we will attempt to set it automatically
			if (count($selected) === 0)
			{
				// If the form name appears in the $_POST array we have a winner!
				if (isset($_POST[$data['name']]))
				{
					$selected = array($_POST[$data['name']]);
				}
			}
			
			$options_vals = '';
			foreach ($options as $key => $val)
			{
				$key = (string) $key;

				if (is_array($val) && ! empty($val))
				{
					$options_vals .= '<optgroup label="'.$key.'">'."\n";

					foreach ($val as $optgroup_key => $optgroup_val)
					{
						$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';

						$options_vals .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
					}

					$options_vals .= '</optgroup>'."\n";
				}
				else
				{
					$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';

					$options_vals .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
				}
			}
			$output = <<<EOL

	<div class="control-group">
		<label class="control-label" for="{$defaults['name']}">{$label}</label>
		<div class="controls">
			 <select {$output} {$extra}>
				{$options_vals}
			</select>
			{$tooltip}
		</div>
	</div>

EOL;

			return $output;
		}
}
