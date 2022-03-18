<?php 

class TableHelper extends TagHelper 
{
	
	public function tableOpen()
	{
		return $this->open('table', ['class' => 'table table-striped']);
	}

	public function tableClose()
	{
		return $this->close('table');
	}

	public function tableHead($tableHeads = [], $attrs = [])
	{
		$result = $this->open('thead');
		$result .= $this->open('tr', $attrs);
		if (!empty($tableHeads)) {
			foreach ($tableHeads as $th) {

				if (!empty($th['thname'])) {
					$thname = $th['thname'];
				} 
					
				if (!empty($th['attrs'])) {
					$attrs = $th['attrs'];
					$attrs['class'] = 'text-center';
				} else {
					$attrs = [];
					$attrs['class'] = 'text-center';
				}

				$result .= $this->open('th', $attrs) . $thname . $this->close('th');
			}	
		}
		$result .= $this->close('tr');
		$result .= $this->close('thead');

		return $result;
	}

	public function tbodyOpen()
	{
		return $this->open('tbody', []);
	}

	public function tbodyClose()
	{
		return $this->close('tbody');
	}

	public function tableBody($tdList = [], $attrs = [])
	{
		$result = $this->open('tr', $attrs);
		if (!empty($tdList)) {
				foreach ($tdList as $td) {
					if (!empty($td['tdname'])) {
						$tdname = $td['tdname'];
					} else {
						$tdname = '';
					}

					if (!empty($td['attrs'])) {
						$attrs = $td['attrs'];
						$attrs['class'] = 'text-center';
					} else {
						$attrs = [];
						$attrs['class'] = 'text-center';
					}
					
					$result .= $this->open('td', $attrs) . $tdname . $this->close('td');
				}	
		}
		$result .= $this->close('tr');

		return $result;
	}
}