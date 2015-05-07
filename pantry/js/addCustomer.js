$(document).ready(function () {	
								
		$('#addCustButton').jqxButton({
			width: 150,
			theme: 'ui-sunny'
		});
		
		$('#attendee').change(function() {
			var YES = '1';
			var htmlStr = '<label for="service" >Service:</label><select name="service"><option value="8">8</option><option value="10">10</option><option value="10:30">10:30</option><option value="12">12</option></select><br/>';

			if(this.value === YES) {
				$('#serviceDiv').html(htmlStr);
			} else {
				$('#serviceDiv').html('N/A');
			}
		});
		
		$('#addCustButton').click(function(event) {
			var params = $('#addCustForm').serialize();

            $.post('addCustomerIframe.php', params, function(resp) {

                parent.$.fancybox.close();
                parent.jQuery.fancybox.close();
                $('#addCustForm').find('input[type=text]').val('');
                $('#state').val('Maryland');
                $('#addCustForm').find('input[type=number]').val(0);
            }).fail(function(xhr, status, error) {
                alert('failure. \n' + xhr);
            });
		});

    $('.us_phone').mask('(000) 000-0000');

});