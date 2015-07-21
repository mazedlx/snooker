<div class="container">
	<div class="row">
		<div class="col-md-9">
			<h1>Frame <?php echo $frame; ?> <div id="result"><?php echo $result_1; ?> - <?php echo $result_2; ?></div></h1>
			<ul class="nav nav-pills" id="team_tabs" data-frame="<?php echo $frame; ?>" data-id-match="<?php echo $id_match; ?>">
				<li role="presentation" class="active" data-id-team="<?php echo $id_team_1; ?>"><a><?php echo $team1; ?></a></li>
				<li role="presentation" data-id-team="<?php echo $id_team_2; ?>"><a><?php echo $team2; ?></a></li>
			</ul>
			<div>

				<h2>Break: <span id="score"><?php echo $score; ?></span></h2>
				<div id="break">
					
				</div>
			</div>
			<hr>
			<div>
				<h3>Points</h3>
				<div class="btn-group" role="group" aria-label="scores">
					<?php
					foreach($scoring_buttons as $button) {
						?>
						<button id="btn_score_<?php echo $button['short']; ?>" data-type="score" data-short="<?php echo $button['short']; ?>" data-value="<?php echo $button['value']; ?>" class="btn btn-default"><div class="<?php echo $button['short']; ?>"></div></button>
						<?
					}
					?>
				</div>
				<h3>Foul</h3>
				<div class="btn-group" role="group" aria-label="fouls">
					<?php
					foreach($foul_buttons as $button) {
						?>
						<button id="btn_foul_<?php echo $button['short']; ?>" data-type="foul" data-short="<?php echo $button['short']; ?>" data-value="<?php echo $button['value']; ?>" class="btn btn-default"><div class="<?php echo $button['short']; ?>"></div></button>
						<?
					}
					?>
				</div>
			</div>
			<hr>
			<button id="btn_save_score" type="button" class="btn btn-primary btn-lg">Break beenden</button>
			<button id="btn_end_match" type="button" class="btn btn-success btn-lg">Frame beenden</button>
			<input type="hidden" id="id_team" value="<?php echo $id_team_1; ?>" />
			<input type="hidden" id="id_team_1" value="<?php echo $id_team_1; ?>" />
			<input type="hidden" id="id_team_2" value="<?php echo $id_team_2; ?>" />
			<input type="hidden" id="id_match" value="<?php echo $id_match; ?>" />
			<input type="hidden" id="frame" value="<?php echo $frame; ?>" />
			<input type="hidden" id="break_text"/>
		</div>
		<div class="col-md-3">
			<h1>Frames</h1>
			<?php echo $frames_table; ?>
		</div>
	</div>
</div>
<script>
$('#team_tabs a').click(function (e) {
	e.preventDefault();
	$(this).tab('show');
	var id_team = $(this).closest('li').attr('data-id-team');
	var frame = $(this).closest('ul').attr('data-frame');
	var id_match = $(this).closest('ul').attr('data-id-match');	
	
	$('#id_team').val(id_team);
	$('#frame').val(frame);
	$('#id_match').val(id_match);
	$('#break').text('');
	$('#break_text').text('');
});

$('[data-type="score"]').click(function() {
	var score = $(this).attr('data-value');
	var old_score = $('#score').text();
	var new_score = parseInt(score)+parseInt(old_score);
	$('#score').text(new_score);
	
	var potted = '<div class="'+$(this).attr('data-short')+'"></div>';
	var potted_text = $(this).attr('data-short');

	var old_break = $('#break').html();
	var old_break_text = $('#break_text').val();

	var new_break = old_break+potted;
	var new_break_text = old_break_text+','+potted_text;

	$('#break').html(new_break);
	$('#break_text').val(new_break_text)
});

$('[data-type="foul"]').click(function() {
	var id_team = $('#id_team').val();
	var id_team_1 = $('#id_team_1').val();
	var id_team_2 = $('#id_team_2').val();
	var frame = $('#frame').val();
	var id_match = $('#id_match').val();
	var score = $('#score').text();
	var break_text = $('#break_text').val();
	var foul = $(this).attr('data-value');	

	$.ajax({
		url: 'ajax/save_score_and_foul',
		type: 'post',
		data:{
			id_team: id_team,
			id_team_1: id_team_1,
			id_team_2: id_team_2,
			frame: frame,
			id_match: id_match,
			score: score,
			break_text: break_text,
			foul: foul
		},
		cache: false
	}).done(function() {
		if(id_team == id_team_1) {
			$('#team_tabs li:eq(1)').tab('show')
			id_team = $('#id_team_2').val();
		} else {
			$('#team_tabs li:eq(0)').tab('show')
			id_team = $('#id_team_1').val();
		}
		$('#id_team').val(id_team);

		$.ajax({
			url: 'ajax/result/',
			type: 'post',
			data: {
				id_match: id_match,
				frame: frame,
				id_team_1: id_team_1,
				id_team_2: id_team_2
			},
			cache: false
		}).done(function(result) {
			$('#result').text(result);
			$('#break').text('');
			$('#break_text').val('');
			$('#score').text(0);
		});
	});
});

$('#btn_save_score').click(function() {
	var id_team = $('#id_team').val();
	var id_team_1 = $('#id_team_1').val();
	var id_team_2 = $('#id_team_2').val();
	var frame = $('#frame').val();
	var id_match = $('#id_match').val();
	var score = $('#score').text();
	var break_text = $('#break_text').val();
	
	$.ajax({
		url: 'ajax/save_score',
		type: 'post',
		data:{
			id_team: id_team,
			frame: frame,
			id_match: id_match,
			score: score,
			break_text: break_text
		},
		cache: false
	}).done(function() {
		if(id_team == id_team_1) {
			$('#team_tabs li:eq(1)').tab('show')
			id_team = $('#id_team_2').val();
		} else {
			$('#team_tabs li:eq(0)').tab('show')
			id_team = $('#id_team_1').val();
		}
		$('#id_team').val(id_team);

		$.ajax({
			url: 'ajax/result/',
			type: 'post',
			data: {
				id_match: id_match,
				frame: frame,
				id_team_1: id_team_1,
				id_team_2: id_team_2
			},
			cache: false
		}).done(function(result) {
			$('#result').text(result);
			$('#break').text('');
			$('#break_text').val('');
			$('#score').text(0);
		});
	});
});

$('#btn_end_match').click(function() {
	$.ajax({
		url: 'ajax/end_match',
		type: 'post',
		data: {

		},
		cache: false
	}).done(function() {
		window.location.reload();
	});
});
</script>