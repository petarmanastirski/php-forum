<?php



class View
{
	private $dtbs;


	function __construct()
	{
		$this->dtbs = Database::getDatabase();
	}


	function tableThreads()
	{
		$content = "";
		if (($threads = $this->dtbs->getThreads()) && count($threads) > 0) {
			 $content .= '<h1>Threads</h1>';
			 $content .= '<table border="0" width="" id="posts_list">';
			 $content .= '<tr>';
			 $content .= '<th class="title">Title</td>';
			 $content .= '<th>Date</td>';
			 $content .= '<th>User</td>';
			 $content .= '</tr>';

			foreach ($threads as $row) {
				$content .= '<tr class="thread">';
				$content .= '<td class="title">';
				$content .= '<a href="view_thread.php?permalink=';
				$content .= htmlspecialchars($row['permalink']) . '">'.$row['title'].'</a>';
				$content .= '</td>';
				$content .= '<td class="date">'.htmlspecialchars($row['date']).'</td>';
				$content .= '<td class="author">'.htmlspecialchars($row['author']).'</td>';
				$content .= '</tr>';
			}
			$content .= '</table>';
			return $content;
		} else {
			return false;
		}
	}


	private function composeTable($post, $firstPost, $numRows)
	{
		$htmlTable = "";

		if ($firstPost)
			$htmlTable .= '<h1>'.htmlspecialchars($post['title']).'</h1>';

		$htmlTable .= '<table border="0" width="895">';
		$htmlTable .= '	<tr>';
		$htmlTable .= '		<th>Message</th>';
		$htmlTable .= '		<th>Date</th>';
		$htmlTable .= '		<th>Author</th>';
		$htmlTable .= '	</tr>';
	   	$htmlTable .= '	<tr>';
		$htmlTable .= '		<td class="title">'.htmlspecialchars($post['content']).'</td>';
		$htmlTable .= '		<td class="date">'.htmlspecialchars($post['date']).'</td>';
		$htmlTable .= '		<td class="author">'.htmlspecialchars($post['author']).'</td>';
		$htmlTable .= '	</tr>';
		$htmlTable .= '</table>';
		if ($firstPost && $numRows > 1)
			$htmlTable .= '<h1>Responses</h1>';

		return $htmlTable;
	}


	function tableThreadContent($permalink)
	{
		$content = "";

		if ($posts = $this->dtbs->getContentThread($permalink)) {
			foreach ($posts as $row) {
				$content .= $this->composeTable($row, is_null($row['permalink_parent']), count($posts));
			}
			return $content;
		} else {
			return false;  //database error
		}
	}


	function htmlError($from_view_thread = false)
	{
		if ($from_view_thread) {
			//From view_thread.php
		   	$html = '<p class="error">There is no thread with this title. Sorry! ';
			$html .= 'You can go back to <a href="index.php">the main page</a>.</p>';
		}else{
			// From index.php
		   	$html = '<p class="error">There aren\'t any threads. Sorry! </p>';
		}
		return $html;
	}


	function messageBox($new, $errorHTML = null)
	{
		$content = '<div id="box_message">';
		if (!empty($errorHTML)) {
			$content .= '<div id="message_header"><p class="error">' . $errorHTML . '</p></div>';
		}
		if ($new) {
			$content .= '<h1>Post a Message</h1>';
		} else {
			$content .= '<h1>Post a Response</h1>';
		}
		$content .= '<form action="" method="post" id="post_message" accept-charset="utf-8">';
		if ($new) {
			$content .= '<input id="input_title" type="text" name="title" value="Title of Thread" size="20" />';
		}
		$content .= '<input type="text" name="author" value="Your Name" size="30" />';
		$content .= '<textarea name="content" rows="8" cols="88">Message</textarea>';
		$content .= '<input type="submit" id="submit_box" value="Post the message" />';
		$content .= '</form>';
		$content .= '</div>';

		return $content;
	}


	function buttonPostThread()
	{
		return '<div class="newThread">'
			  .'<a href="post_message.php">Create a new thread</a>'
			  .'</div>';
	}
}
?>