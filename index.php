<?php

require_once 'classes/View.php';
require_once 'classes/Database.php';
require_once 'header.html';
if ( '' == file_get_contents( 'config.php' ) )
{
	header("Location: install.php");
}
$view = new View();



if ($htmlString = $view->tableThreads()) {
	echo $htmlString;
} else {
	echo $view->htmlError();
}

echo $view->buttonPostThread();

require_once 'footer.html';
