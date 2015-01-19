<?php
require_once 'classes/View.php';
require_once 'validation.php';
require_once 'header.html';

$result = processPost();	 

if ($result['showBox']) {
	$view = new View(); 
	echo $view->messageBox(true, $result['errorHTML']);
} else {
	echo $result['okMessage'];
}      

require_once 'footer.html'; 
?>