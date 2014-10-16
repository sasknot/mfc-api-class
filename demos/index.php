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
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/bootstrap-theme.min.css" />
	<style>
		body {
			padding-top: 40px;
		}

		.results .tab-pane {
			padding: 20px 0;
		}
	</style>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/imagesloaded.pkgd.min.js"></script>
	<script type="text/javascript" src="js/masonry.pkgd.min.js"></script>
	<script>
		$(document).ready(function() {
			var $list = $('.results .list');

			$list.imagesLoaded(function(){
				$list.masonry();
			});
		});
	</script>
</head>
<body>
	<div class="container">
		<div class="jumbotron">
			<h1>MFC API Class Demo</h1>
			<p>
				Want to know how much you spent in figures? Here is your tool to calculate this.<br />
				Just input username from <abbr title="MyFigureCollection" class="initialism">MFC</abbr> below.
			</p>
		</div>
		<form role="form" action="" method="post" class="well form-horizontal">
			<div class="clearfix">
				<label for="username" class="col-sm-2 control-label">Username:</label>
				<div class="col-xs-6">
					<input type="text" id="username" name="username" class="form-control" />
				</div>
				<div class="col-xs-4 col-sm-3">
					<button type="submit" class="btn btn-default">Search</button>
				</div>
			</div>
		</form>
		<?php if( isset($items) && isset($values) ) { ?>
		<hr>
		<dl class="dl-horizontal">
			<dt>Username</dt>
			<dd><?php echo $_POST['username']; ?></dd>
			<dt>Items total</dt>
			<dd><?php echo $items['total']; ?></dd>
			<dt>Spent total</dt>
			<dd><?php echo $values['total']; ?> <small>yen</small></dd>
		</dl>
		<hr>
		<ul role="tablist" class="nav nav-tabs">
			<li class="active">
				<a role="tab" href="#grid" data-toggle="tab"><span class="glyphicon glyphicon-th-large"></span> Grid View</a>
			</li>
			<li>
				<a role="tab" href="#table" data-toggle="tab"><span class="glyphicon glyphicon-list"></span> Table View</a>
			</li>
		</ul>
		<div class="tab-content results">
			<div id="grid" class="tab-pane active">
				<div class="row list">
					<?php
						foreach($items['data'] as $key => $item)
						{
							$score = $item['mycollection']['score'];

							if( $score < 0 )
							{
								$score = "none";
							}
					?>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="thumbnail">
							<h4 class="text-center"><?php echo $item['data']['name']; ?></h4>
							<img src="http://s1.tsuki-board.net/pics/figure/big/<?php echo $item['data']['id']; ?>.jpg" alt="" />
							<div class="caption">
								<p>
									ID: <strong>#<?php echo $item['data']['id']; ?></strong><br />
									Price: <strong><?php echo $item['data']['price']; ?></strong><br />
									Release Date: <strong><?php echo $item['data']['release_date']; ?></strong><br />
									Score Given: <strong><?php echo $score; ?></strong><br />
									Wishability: <strong><?php echo $item['mycollection']['wishability']; ?></strong>
								</p>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div id="table" class="tab-pane">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Price</th>
							<th>Release Date</th>
							<th>Score Given</th>
							<th>Wishability</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach($items['data'] as $item)
							{
								$score = $item['mycollection']['score'];

								if( $score < 0 )
								{
									$score = "none";
								}

								$imgURL = "http://s1.tsuki-board.net/pics/figure/" . $item['data']['id'] . ".jpg";
						?>
						<tr>
							<td><?php echo $item['data']['id']; ?></td>
							<td><?php echo $item['data']['name']; ?></td>
							<td><?php echo $item['data']['price']; ?></td>
							<td style="white-space:nowrap;"><?php echo $item['data']['release_date']; ?></td>
							<td><?php echo $score; ?></td>
							<td><?php echo $item['mycollection']['wishability']; ?></td>
							<td>
								<img src="<?php echo $imgURL; ?>" alt="" class="img-thumbnail" />
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php } ?>
	</div>
</body>
</html>