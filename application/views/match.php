<div class="container">
	<div class="row">
		<div class="col-md-9">
			<h1>Match</h1>
			<?php echo form_open('match/add', array('role' => 'form', 'class' => 'form-horizontal')); ?>
				<div class="form-group">
					<?php echo form_label('Team 1', 'id_team_1', array('class' => 'col-md-3 control-label')); ?>
					<div class="col-md-6">
						<?php echo form_dropdown(array('id' => 'id_team_1', 'name' => 'id_team_1', 'class' => 'form-control'), $id_teams, $id_team_1); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Team 2', 'id_team_2', array('class' => 'col-md-3 control-label')); ?>
					<div class="col-md-6">
						<?php echo form_dropdown(array('id' => 'id_team_2', 'name' => 'id_team_2', 'class' => 'form-control'), $id_teams, $id_team_2); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-3 col-md-6">
						<button type="submit" id="btn_submit" class="btn btn-primary">Match starten</button>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>