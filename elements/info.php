<?php
	'<div class="row justify-content-center m-2">';
		if($session->exists('message')) {
			$status = $_SESSION['message']['status'];
			$text = $_SESSION['message']['text'];

			if ($status == 'success') {
				$status = 'text-success';
			} else {
				$status = 'text-danger';
			}

			echo "<div class=\"col-sm-12 col-md-12 col-lg-12 text-center\"><h4 class=\"$status font-weight-bold\">$text</h4></div>";
			$session->del('message');
		}
	'</div>';