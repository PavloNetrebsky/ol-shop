<div class="wrap">
	<?php
		if ( $type == 'class' ) {
			printf( '<h1>%s</h1>',
				esc_html__( 'Sync & Import Classes', 'noo-timetable' )
			);
		} else {
			printf( '<h1>%s</h1>',
				esc_html__( 'Sync & Import Events', 'noo-timetable' )
			);
		}
	?>
	<div id="col-container" class="wp-clearfix noo-class-import-sync">
		<?php if ( isset($_REQUEST['new_run_cron']) && $_REQUEST['new_run_cron'] == 1 ) : ?>
			<div class="noo-notice notice-success"><?php esc_html_e( 'Successfully executed.', 'noo-timetable' ); ?></div>
		<?php elseif ( isset($_REQUEST['new_saved_cron']) && $_REQUEST['new_saved_cron'] == 1 ) : ?>
			<div class="noo-notice notice-success"><?php esc_html_e( 'Recurring Import saved.', 'noo-timetable' ); ?></div>
		<?php else : ?>
			<div class="noo-notice"></div>
		<?php endif; ?>
		<div id="col-left" class="import-ical">
			<table class="form-table">
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="noo-import-type"><?php esc_html_e( 'Import Type', 'noo-timetable' ); ?></label>
					</th>
					<td class="forminp">
						<select name="noo-import-type" id="noo-import-type">
							<option value="0">
								<?php esc_html_e( 'Select Type', 'noo-timetable' ); ?>
							</option>
							<option value="1" class="single">
								<?php esc_html_e( 'One-Time Import', 'noo-timetable' ); ?>
							</option>
							<?php
							$cron_schedules = self::get_schedule_cron();
							foreach ( $cron_schedules as $key => $value ) {
								?>
								<option value="<?php echo esc_attr( $key ); ?>" class="recurring">
									<?php echo esc_html__( 'Recurring', 'noo-timetable' ) . ': ' . $value[ 'display' ]; ?>
								</option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="noo-import-source"><?php esc_html_e( 'Import Source', 'noo-timetable' ); ?></label>
					</th>
					<td class="forminp">
						<select name="noo-import-source" id="noo-import-source">
							<option value="url">
								<?php esc_html_e( 'iCal URL', 'noo-timetable' ); ?>
							</option>
							<option value="file">
								<?php esc_html_e( '.ics File', 'noo-timetable' ); ?>
							</option>
						</select>
						<input type="button" id="noo-ical-upload" class="button-secondary" value="<?php esc_attr_e( 'Click to choose', 'noo-timetable' ); ?>">
						<input type="text" name="ical_url" id="noo-import-url" placeholder="<?php esc_attr_e( 'example.com/ical-url', 'noo-timetable' ); ?>" value="" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="noo-import-start"><?php esc_html_e( 'Import Start', 'noo-timetable' ); ?></label>
					</th>
					<td class="forminp">
						<input id="noo-import-start" type="text" placeholder="<?php esc_attr_e( 'Start Date', 'noo-timetable' ); ?>" class="input-import-start" value="<?php echo date('Y-m-d'); ?>">
					</td>
				</tr>
				<?php
					if ( $type == 'class' )
						include 'html-class-output.php';
					else
						include 'html-event-output.php';
				?>
				<tr valign="top">
					<th scope="row" class="titledesc">&nbsp;</th>
					<td class="forminp">
						<input type="hidden" id="noo-import-post-type" value="<?php echo ($type == 'class') ? 'class' : 'event'; ?>">
						<input type="button" id="noo-sync-preview" class="button-secondary" value="<?php esc_html_e( 'Preview', 'noo-timetable' ); ?>" />
						<input type="button" id="noo-sync-import-all" class="button-primary" value="<?php esc_html_e( 'Import All', 'noo-timetable' ); ?>" style="display: none;" />
						<input type="button" id="noo-sync-save-recurring" class="button-primary" value="<?php esc_html_e( 'Save Recurring', 'noo-timetable' ); ?>" style="display: none;" />
					</td>
				</tr>
			</table>
			<div class="wrap-result-preview"></div>
		</div>
		<?php self::list_recurring_imports( $type ); ?>
	</div> <!-- /#col-container -->
</div> <!-- /.wrap -->