<?php
define('CHANGES_PERCENT', 10);
if (isset($_POST['Text'])){
	$Result = array(
		'deleted' => array(),
		'new' => array(),
		'changed' => array(),
	);
	$SentencesOriginal = getSenteces($_POST['Text']['Original']);
	$SentencesToCompare = getSenteces($_POST['Text']['ToCompare']);

	$Diff1 = array_diff($SentencesOriginal[0], $SentencesToCompare[0]);
	$Diff2 = array_diff($SentencesToCompare[0], $SentencesOriginal[0]);

	foreach ($Diff2 as $sentKey2 => $sentence1){
		$new = true;

		foreach ($Diff1 as $sentKey1 => $sentence2){
			$difference = difference($sentence1, $sentence2);

			if ($difference < CHANGES_PERCENT){
				$new = false;
				break;
			}
		}

		if ($new === false){
			$Result['changed'][$sentKey2] = array($sentence1, $sentence2, $difference);
		}

		if ($new === true){
			$Result['new'][$sentKey2] = $sentence1;
		}
	}

	foreach ($Diff1 as $sentKey1 => $sentence1){
		$deleted = true;

		foreach ($Diff2 as $sentKey2 => $sentence2){
			$difference = difference($sentence1, $sentence2);

			if ($difference < CHANGES_PERCENT){
				$deleted = false;
				break;
			}
		}

		if ($deleted === true){
			$Result['deleted'][$sentKey1] = $sentence1;
		}
	}
}

function getSenteces($text){
	//$Sentences = preg_split("|(?<=[.?!:\n])\s+?(?=[^\s])|iu", strip_tags($text));
	preg_match_all('|([^.?!:\n])+([.?!:\n\r])+\s+?|iu', $text, $Sentences);

	return !empty($Sentences) ? $Sentences : null;
}

function difference($s1, $s2){
	$s1 = preg_replace("|\p{Cc}+|u", '', $s1);
	$s2 = preg_replace("|\p{Cc}+|u", '', $s2);

	$result = levenshtein($s1, $s2) / strlen($s1)*100;
	//echo '<li>' . $s1 . ' => ' . $s2 . '(' . $result . ')<br>';
	return $result;
}

include 'view/index.php';
?>