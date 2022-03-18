<?php 

class FormHelper extends TagHelper 
{
	public function openForm($attrs = [])
	{
		return $this->open('form', $attrs);
	}

	public function closeForm()
	{
		return $this->close('form');
	}

	public function input($attrs = [], $labelName = '')
	{
		if (isset($attrs['name'])) {
			$name = $attrs['name'];
		}
		$result = '<div class="form-group mt-2 mb-2">';

		if($labelName != '') {
			$result .= "<label for=\"$name\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function date($attrs = [], $labelName = '')
	{
		if (isset($attrs['name'])) {
			$name = $attrs['name'];
		}
		$attrs['type'] = 'date';
		$result = '<div class="form-group mt-2 mb-2">';

		if($labelName != '') {
			$result .= "<label for=\"$name\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function time($attrs = [], $labelName = '')
	{
		if (isset($attrs['name'])) {
			$name = $attrs['name'];
		}
		$attrs['type'] = 'time';
		$result = '<div class="form-group mt-2 mb-2">';

		if($labelName != '') {
			$result .= "<label for=\"$name\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function datetime($attrs = [], $labelName = '')
	{
		if (isset($attrs['name'])) {
			$name = $attrs['name'];
		}
		$attrs['type'] = 'datetime-local';
		$result = '<div class="form-group mt-2 mb-2">';

		if($labelName != '') {
			$result .= "<label for=\"$name\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function number($attrs = [], $labelName = '')
	{
		if (isset($attrs['name'])) {
			$name = $attrs['name'];
		}
		$attrs['type'] = 'number';
		$result = '<div class="form-group mt-2 mb-2">';

		if($labelName != '') {
			$result .= "<label for=\"$name\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function phone($attrs = [], $labelName = '')
	{
		if (isset($attrs['name'])) {
			$name = $attrs['name'];
		}
		$attrs['type'] = 'phone';
		$result = '<div class="form-group mt-2 mb-2">';

		if($labelName != '') {
			$result .= "<label for=\"$name\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function password($attrs = [], $labelName)
	{
		$attrs['type'] = 'password';
		$result = '<div class="form-group mt-1 mb-1">';

		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"m-0\">$labelName</label>";
		}
				
		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function fileInput($attrs = [], $labelName)
	{
		$attrs['type'] = 'file';
		$result = '<div class="form-group mt-1 mb-1">';

		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"m-0\">$labelName</label>";
		}
				
		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function fileInputHidden($attrs = [], $labelName)
	{
		$attrs['type'] = 'file';
		$result = "<div id=\"{$attrs['id']}\" class=\"form-group mt-1 mb-1\" style=\"display:none\">";

		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"m-0\">$labelName</label>";
		}
				
		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function textInputHidden($attrs = [], $labelName)
	{
		$attrs['type'] = 'text';
		$result = "<div id=\"{$attrs['id']}\" class=\"form-group mt-1 mb-1\" style=\"display:none\">";

		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"m-0\">$labelName</label>";
		}
				
		$result .= $this->open('input', $attrs);
		$result .= '</div>';
		return $result;
	}

	public function hidden($attrs = [])
	{
		$attrs['type'] = 'hidden';
		return $this->open('input', $attrs);
	}

	public function submit($attrs = [])
	{
		$attrs['type'] = 'submit';
		return $this->open('input', $attrs);
	}

	public function checkbox($attrs = [], $labelName = '')
	{
		$attrs['type'] = 'checkbox';
		$attrs['value'] = 1;

		$result = '<div class="form-check mt-1 mb-1">';
		
		$result .= $this->open('input', $attrs);
		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"form-check-label\">$labelName</label>";
		}
		$result .= '</div>';
		return $result;
	}

	public function search($attrs = [])
	{
		$attrs['type'] = 'search';
		return $this->open('input', $attrs);
	}

	public function textarea($text = '', $attrs = [], $labelName = '')
	{
		$result = '<div class="form-group mt-1 mb-1">';
		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"m-0\">$labelName</label>";
		}

		$result .= $this->open('textarea', $attrs) . $text . $this->close('textarea');
		$result .= '</div>';
		return $result;
	}

	public function select($attrs = [], $options = [], $labelName = '')
	{
		$result = '<div class="form-group mt-1 mb-1">';
		if($labelName != '') {
			$result .= "<label for=\"{$attrs['name']}\" class =\"m-0\">$labelName</label>";
		}
		$result .= $this->open('select', $attrs);
		if (isset($attrs['name'])) {
			
			$name = $attrs['name'];

			if (!empty($options)) {
				foreach ($options as $option) {
					if (!empty($option['text'])) {
						$text = $option['text'];
					} 

					if (!empty($option['attrs'])) {
						$attrs = $option['attrs'];
					} else {
						$attrs = [];
					}
					
					$result .= $this->open('option', $attrs) . $text . $this->close('option');
				}
			}
			
		} else {
			$result .= $this->open('select');
		}
		$result .= $this->close('select');
		$result .= '</div>';
		return $result;
	}

	public function modalButton($text = '', $attrs = [])
	{
		$attrs['type'] = 'button';
		$attrs['data-toggle'] = 'modal';

		return $this->open('button', $attrs) . $text . $this->close('button');
	}

	public function modalBody($modalId, $titleModalText, $modalBodyText)
	{
		$result = "<div class=\"modal fade\" id=\"modal-$modalId\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"label-$modalId\" aria-hidden=\"true\">";
  		$result .= '<div class="modal-dialog" role="document">
    				<div class="modal-content">
      				<div class="modal-header">';
        $result .= "<h5 class=\"modal-title\" id=\"label-$modalId\">$titleModalText</h5>";
        $result .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	         		<span aria-hidden="true">&times;</span>
	        		</button>
	      			</div>';
      $result .= "<div class=\"modal-body\">
        		$modalBodyText
      </div>";
      $result .= '<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
     			</div>
    			</div>
  				</div>
				</div>';
	return $result;
	}
}