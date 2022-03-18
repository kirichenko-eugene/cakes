<?php 

class TagHelper 
{
	public function open($name, $attrs = [])
	{
		$attrsStr = $this->getAttrsStr($attrs);
		return "<$name$attrsStr>";
	}

	public function close($name)
	{
		return "</$name>";
	}

	public function getAttrsStr($attrs)
	{
		if (!empty($attrs)) {
			$result = '';

			foreach ($attrs as $name => $value) {
				if ($value === true) {
					$result .= " $name";
				} else {
					$result .= " $name=\"$value\"";
				}
			}
			return $result;
		} else {
			return '';
		}
	}

	public function show($name, $text)
	{
		return $this->open($name) . $text . $this->close($name);
	}
}