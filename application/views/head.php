<?php
$class = 'class="active"';
switch($active) {
	case 'teams':
		$active_teams = $class;
		break;
	case 'match':
		$active_match = $class;
		break;
	case 'scoring':
		$active_scoring = $class;
		break;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Snooker Score</title>
		<base url="<?php echo base_url(); ?>" />
		<?php echo link_css('bootstrap'); ?>
		<?php echo link_css('bootstrap-theme'); ?>
		<?php echo link_css('additional'); ?>
		<?php echo link_js('jquery-2.1.4.min'); ?>
		<?php echo link_js('bootstrap.min'); ?>
	</head>
	<body>
		<nav class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Snooker Score</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Snooker Score</a>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li <?php echo $active_teams; ?>><a href="teams">Teams</a></li>
					<li <?php echo $active_match; ?>><a href="match">Match</a></li>
					<li <?php echo $active_scoring; ?>><a href="scoring">Scoring</a></li>
					<li><a href="ajax/logoff">Beenden</a></li>
				</ul>			
			</div>
		</nav>
		<?php
		if($this->session->flashdata('msg')) {
		?>
		<div class="container">
			<div class="row">
				<div id="msg" class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $this->session->flashdata('msg'); ?>
				</div>
			</div>
		</div>
		<?
		}
		?>