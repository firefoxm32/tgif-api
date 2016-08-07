<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<label for="txt_id">ID</label>
	<input type="text" id="txt_id" name="txt_id">
	<label for="txt_tableno">Table #</label>
	<input type="text" id="txt_tableno" name="txt_tableno">
	<button class="js-button-post">Post</button>
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript">
	var post = function() {
		// var $txtId = $('#txt_id').val(),
		// 	$tableNo = $('#txt_tableno').val();

		$('.js-button-post').on('click', function(e) {
			e.preventDefault();
			console.log('click');
			console.log( $('#txt_id').val());
			console.log($('#txt_tableno').val());
			$.ajax({
				url: 'http://192.168.1.100/tgif/api/add-order.php',
				data: {
					table_number: $('#txt_id').val(),
					item_id: $('#txt_tableno').val()
				},
				type: 'post',
				success: function(result) {
					console.log(result);
				}
			});
		});
	};

	$(function(){
		post();
	});


</script>
</body>
</html>
