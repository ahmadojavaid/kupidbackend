
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		</div>
		<link rel="stylesheet" type="text/css" media="screen" href="https://cdn.conversejs.org/css/converse.min.css">
		<script>
		var BASE_URL='<?php echo base_url();?>';
		</script>
		<script src="https://cdn.conversejs.org/dist/converse.min.js"></script>
		<!--   Core JS Files   -->
		<script src="<?php  echo base_url("Newassets/admin/js/jquery-1.10.2.js"); ?>" type="text/javascript"></script>
		<script src="<?php  echo base_url("Newassets/admin/js/bootstrap.min.js"); ?>" type="text/javascript"></script>
		<script type="text/javascript" src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js<?php  //echo base_url("Newassets/admin/js/bootstrap-toggle.min.js"); ?>"></script>
		<!--  Checkbox, Radio & Switch Plugins -->
		<script src="<?php  echo base_url("Newassets/admin/js/bootstrap-checkbox-radio-switch.js"); ?>"></script>
		<!--  Charts Plugin -->
		<script src="<?php  echo base_url("Newassets/admin/js/chartist.min.js"); ?>"></script>
		<!--  Notifications Plugin    -->
		<script src="<?php  echo base_url("Newassets/admin/js/bootstrap-notify.js"); ?>"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<!--  Google Maps Plugin    -->
		<!-- Light Bootstrap Table Core javascript and methods for Demo purpose --> 
		<script src="<?php  echo base_url("Newassets/admin/js/light-bootstrap-dashboard.js"); ?>"></script>
		<script src="<?php  echo base_url("Newassets/admin/js/owl.carousel.min.js"); ?>"></script>
		<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
		<script src="<?php  echo base_url("Newassets/admin/js/custom.js"); ?>"></script>
		<!-- <script src="<?php  echo base_url("Newassets/admin/js/location.js"); ?>"></script> -->
		<script src="<?php  echo base_url("Newassets/admin/js/style-customizer.js"); ?>"></script>
		<script src="<?php  echo base_url("Newassets/crop/dist/cropper.min.js"); ?>" type="text/javascript"></script>
		<script src="<?php  echo base_url("Newassets/crop/js/main.js"); ?>" type="text/javascript"></script>
		<script src="<?php  echo base_url("Newassets/js/gallery.js"); ?>" type="text/javascript"></script>
		<script>
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose:true
		});
		</script>
	</body>
</html>