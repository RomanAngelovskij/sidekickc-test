<!doctype html>
<html lang="en-gb" class="no-js">

<head>
	<title>Тестовое задание 4</title>
	
	<meta charset="utf-8">
	<meta name="keywords" content="" />
	<meta name="description" content="" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

	<?php
	$comparedText = '';
	if (isset($SentencesToCompare) && !empty($SentencesToCompare[0])){
		foreach ($SentencesToCompare[0] as $key => $sentence){
			$class = '';
			$original = '';
			$changed = '';
			$dataToggle = '';
			$title = '';

			if (isset($Result['new'][$key])){
				$class = 'new';
			}

			if (isset($Result['changed'][$key]) && $Result['changed'][$key][2] > 0){
				$class = 'changed';
				$original = nl2br(preg_replace('|"|', '&quot;', $Result['changed'][$key][1]));
				$changed = nl2br(preg_replace('|"|', '&quot;', $Result['changed'][$key][0]));
				$dataToggle = 'data-toggle="tooltip"';
				$title = round($Result['changed'][$key][2], 2) . '%';
			}

			if (isset($Result['deleted'][$key])){
				$comparedText .= '<span class="deleted">' . nl2br($SentencesOriginal[0][$key]) . '</span>';
				unset($Result['deleted'][$key]);
			}

			$comparedText .= '<span class="' . $class . '" data-original="' . $original . '" data-changed="' . $changed . '"' . $dataToggle . 'data-placement="top" title="' . $title . '">' . nl2br($sentence) . '</span>';
		}

		if (!empty($Result['deleted'])){
			foreach ($Result['deleted'] as $key => $sentence){
				$comparedText .= '<span class="deleted">' . nl2br($sentence[0]) . '</span>';
			}
		}
	}
	?>
	<script>
		var comparedText = `<?=$comparedText?>`;
	</script>
	
	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="css/main.css" type="text/css" />
</head>

<body>
	<div class="col-md-10">
		<?php if (isset($Errors) && !empty($Errors)):?>
			<div class="alert alert-danger">
				Ошибки:<br>
				<?php foreach($Errors as $error):?>
					<li><?=$error?>
				<?php endforeach;?>
			</div>
		<?php endif;?>

		<form action="" method="post" id="compare-form">
			<div class="col-md-6">
				<div class="form-group">
					<label for="original">Начальная версия</label>
					<textarea name="Text[Original]" id="original" class="form-control"><?= isset($_POST['Text']['Original']) ? $_POST['Text']['Original'] : '';?></textarea>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="to-compare">Новая версия</label>
					<textarea name="Text[ToCompare]" id="to-compare" class="form-control"><?= isset($_POST['Text']['ToCompare']) ? $_POST['Text']['ToCompare'] : '';?></textarea>
				</div>
			</div>

			<div class="col-md-12">
				<button class="btn btn-primary form-control">Сравнить</button>
			</div>
		</form>

		<div class="clearfix"></div>

		<div id="compared-wrap"></div>
	</div>
	<div class="col-md-2">
		<p>
			<span class="changed">Измененные предложения</span> - предложение считается отредактированным, если в нем не более <?=CHANGES_PERCENT?>% перестановок в тексте.
		</p>
		<p>
			<span class="new">Новые предложения</span>
		</p>
		<p>
			<span class="deleted">Удаленные предложения</span>
		</p>
	</div>
</body>
