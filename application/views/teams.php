<div class="container">
	<div class="row">
		<div class="col-md-9">
			<h1>Teams</h1>
			<?php echo form_open('teams/add', array('role' => 'form', 'class' => 'form-horizontal')); ?>
				<div class="form-group">
					<?php echo form_label('Team 1', 'team_1', array('class' => 'control-label col-md-3')); ?>
					<div class="col-md-6">
						<?php echo form_input(array('id' => 'team_1', 'name' => 'team_1', 'class' => 'form-control', 'placeholder' => 'Team 1', 'required' => 'required')); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Team 2', 'team_2', array('class' => 'control-label col-md-3')); ?>
					<div class="col-md-6">
						<?php echo form_input(array('id' => 'team_2', 'name' => 'team_2', 'class' => 'form-control', 'placeholder' => 'Team 2')); ?>
					</div>
				</div>		
				<div class="form-group">
					<div class="col-md-offset-3 col-md-6">
						<button type="submit" class="btn btn-primary">Speichern</button>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>