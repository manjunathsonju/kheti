<?php
ini_set("display_errors", "on");
error_reporting(-1);

class ControllerCheckouttest extends Controller {
	public function index() {
		$fp = fopen('debug_3098.txt', 'w');
		fwrite($fp, "Kaali Ma");
		fclose($fp);
		echo "Kaali Ma";
	}
}
?>