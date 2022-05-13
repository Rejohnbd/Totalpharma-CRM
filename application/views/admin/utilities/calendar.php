<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body" style="overflow-x: auto;">
						<div class="dt-loader hide"></div>
						<?php $this->load->view('admin/utilities/calendar_filters'); ?>
						<div id="calendar"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php hooks()->do_action('after_calendar_loaded');?>
<script>
	app.calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>

<?php require('modules/appointly/assets/js/modals/create_js.php'); ?>
<?php require 'modules/appointly/assets/js/index_main_js.php'; ?>
<?php if ($appointly_outlook_client_id !== '' && strlen($appointly_outlook_client_id) === 36) : ?>
    <?php require 'modules/appointly/assets/js/outlook_js.php'; ?>
<?php endif; ?>
<script>
	$(function(){
		if(get_url_param('eventid')) {
			view_event(get_url_param('eventid'));
		}

		if (!isOutlookLoggedIn()) {
           $('body').find('#showOutlookCheckbox').remove();
	    } else {
	        acquireTokenPopupAndCallMSGraph();
	    }
	});
</script>
</body>
</html>
<?php
	/**
	 * This file is newly created.
	 * Old file is exist named calender_old.php
	 * Line no: 25 to 29 and 36 to 40 added here.
	 * This lines are not mendatory. You can rearragne this. 
	 */
?>