<?php



function processPost($permalink = null, $newThread = true)
{
	$input = $_POST;

	//The array we'll return
	$returnArray = array('errorHTML' => NULL,
						 'okMessage' => NULL,
						 'showBox' => NULL);

	if (!empty($input) > 0) {
		//We have something in the $input

            $dtbs = Database::getDatabase();

			if (is_null($permalink)){
				$permalink = str_replace(' ', '-', $input['title']);
			}

			$saveResult = $dtbs->savePost($input, $permalink, $newThread);

			switch ($saveResult) {
				case -1:  //A field is empty
					$returnArray['errorHTML'] = 'All fields are required';
					break;

				case -2: //The title is already in use
					$returnArray['errorHTML'] = 'Duplicated title, please, choose another one.';
					break;
				case -3: //There has been an error with the query
					$returnArray['errorHTML'] = 'There has been an error with the Database, '.
											'please, try again later.';
					break;

				default: //Everything is OK
					$returnArray['okMessage'] = '<p>The post has been published. You can see it '
						.'<a href="view_thread.php?permalink=' . $permalink . '">here</a></p>';
			}
		}


	if ($newThread) {
		$returnArray['showBox'] = !empty($returnArray['errorHTML'])
								  || empty($input);
	}

	return $returnArray;
}
