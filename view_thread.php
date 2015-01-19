<?php
require_once 'classes/View.php';
require_once 'validation.php';
require_once 'header.html';

$view = new View(); 
$permalink = clean($_GET['permalink']);

$result = processPost($permalink, false);

$messageBox = $view->messageBox(false,$result['errorHTML']); 

if ($htmlString = $view->tableThreadContent($permalink)) {
	echo $htmlString;
	echo $messageBox;
} else {
	echo $view->htmlError(true); 
}

require_once 'footer.html';
?>                                                                                                                                            