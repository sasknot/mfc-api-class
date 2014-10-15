<?php
	require_once "../MyFigureCollection.php";
	
	if( $_SERVER['REQUEST_METHOD'] == "POST" )
	{
		$MFC = new MyFigureCollection($_POST['username']);

		$MFC->query();

		$items = $MFC->items;
		$values = $MFC->values;
	}

?><!DOCTYPE html>
<html lang="en_US">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>MFC API Class Demo</title>
	<link rel="stylesheet" href="normalize.css" />
	<style>
		body {
			font: 12px/14px Arial,Helvetica,sans-serif;
			padding: 10px;
		}
	</style>
</head>
<body>
	<h1>MFC API Class Demo</h1>
	<form action="" method="post">
		<fieldset>
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" />
			<button type="submit">Search</button>
		</fieldset>
	</form>
	<?php if( isset($items) && isset($values) ) { ?>
	<?php //var_dump($items['data']); ?>
	<hr>
	<h3>Username: <?php echo $_POST['username']; ?></h3>
	<h3>Items total: <?php echo $items['total']; ?></h3>
	<h3>Spent total: <?php echo $values['total']; ?> (in the currency you inputted at MFC)</h3>
	<hr>
	<h4>Lista:</h4>
	<ul>
	<?php
		foreach($items['data'] as $item)
		{
			$score = $item['mycollection']['score'];

			if( $score < 0 )
			{
				$score = "none";
			}

			echo <<<STR
			<li>
				<img src="http://s1.tsuki-board.net/pics/figure/{$item['data']['id']}.jpg" alt="" style="float:right;" />
				<p>ID: <strong>#{$item['data']['id']}</strong></p>
				<p>Name: <strong>{$item['data']['name']}</strong></p>
				<p>Price Given: <strong>{$item['data']['price']}</strong></p>
				<p>Release Date: <strong>{$item['data']['release_date']}</strong></p>
				<p>Score Given: <strong>{$score}</strong></p>
				<p>Wishability: <strong>{$item['mycollection']['wishability']}</strong></p>
				<hr style="clear:both;">
			</li>
STR;
		}
	?>
	</ul>
	<?php } ?>
</body>
</html>