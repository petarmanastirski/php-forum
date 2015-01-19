<?php
require_once 'classes/View.php';
require_once 'header.html';

$view = new View(); 

if ($htmlString = $view->tableThreads()) {
	echo $htmlString;
} else {
	echo $view->htmlError();   
}
  
echo $view->buttonPostThread();	       

require_once 'footer.html'; 
?>