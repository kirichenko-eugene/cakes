<?php 

class Pagination
{
	private $links;
	private $perPage;
	private $page;
	private $pagesCount;
	
	public function __construct()
	{
		if(empty($_GET['page']) OR ($_GET['page'] <= 0)) {
			$this->page = 1;
		} else {
			$this->page = (int) $_GET['page'];
		}
	}

	public function setPerPage($perPage)
	{
		$this->perPage = $perPage;
	}

	public function getPerPage()
	{
		return $this->perPage;
	}

	public function setPagesCount($countElements)
	{
		$this->pagesCount = ceil($countElements / $this->getPerPage());
	}

	public function startPosition()
	{
		$startPosition = ($this->page - 1) * $this->getPerPage();
		return $startPosition;
	}

	public function setLinksNumber($num)
	{
		$this->links = $num;
	}

	private function getPagesCount()
	{
		return $this->pagesCount;
	}

	private function getPage()
	{
		return $this->page;
	}

	private function getLinksNumber()
	{
		return $this->links;
	}

	private function beginPagination()
	{
		$begin = $this->page - intval($this->getLinksNumber() / 2);
		return $begin;
	}

	public function showPagination()
	{
		$pagesCount = $this->getPagesCount();
		$links = $this->getLinksNumber();
		$begin = $this->beginPagination();
		$page = $this->getPage();
		$showLinks = '';

		$showLinks .= '<div class="row justify-content-center m-2">
							<nav>
				  			<ul class="pagination">';

		if ($pagesCount == 1 OR $pagesCount < 1) return false;
		unset($showDots);

		if ($pagesCount <= $links + 1) $showDots = 'no';
		if (($begin > 2) && !isset($showDots) && ($pagesCount - $links > 2)) {

			$showLinks .= "<li class=\"page-item\">
							<a href=\"{$_SERVER['PHP_SELF']}?page=1\" aria-label=\"Start\" class=\"page-link\">
							&laquo;
							</a>
							</li>";
		}

	for ($j = 0; $j <= $links; $j++) {
		$i = $begin + $j; 
		if ($i < 1) {
			$links++;
			continue;
		}
		
		if (!isset($showDots) && $begin > 1) {
			
			$showLinks .= "<li class=\"page-item\">
		 			<a href=\"{$_SERVER['PHP_SELF']}?page=$i - 1\" aria-label=\"...-\" class=\"page-link\">
					...
		 			</a>
		 			</li>";
		 	$showDots = "no";
		}
		if ($i > $pagesCount) break;
		if ($i == $page) {
			$showLinks .= "<li class=\"page-item disabled\">
		 			<a href=\"#\" class=\"page-link\">
					<b>$i</b>
		 			</a>
		 			</li>";
		} else {
			$showLinks .= "<li class=\"page-item\">
		 			<a href=\"{$_SERVER['PHP_SELF']}?page=$i\" class=\"page-link\">
		 			$i
		 			</a>
		 			</li>";
		}

		if (($j == $links) && ($i < $pagesCount)) {
			$showLinks .= "<li class=\"page-item\">
		 			<a href=\"{$_SERVER['PHP_SELF']}?page=$i + 1\" aria-label=\"...+\" class=\"page-link\">
					...
		 			</a>
		 			</li>";
		}	
	}

	if ($begin + $links + 1 < $pagesCount) {

		$showLinks .= "<li class=\"page-item\">
		<a href=\"{$_SERVER['PHP_SELF']}?page=$pagesCount\" aria-label=\"End\" class=\"page-link\">
				&raquo;
				</a>
				</li>";
	}

	$showLinks .= '</ul>
		</nav>
		</div>';

	return $showLinks;
	}
}

