$(document).ready(function () {	
								
		$('#addCustButton').jqxButton({
			width: 150,
			theme: 'ui-sunny'
		});

    $('#serviceRow').hide();

    $("#phoneInput").jqxMaskedInput({
        mask: '(###)###-####',
        width: 150,
        height: 22
    });

    $('#zipInput').jqxMaskedInput({
        mask: '#####',
        width: 150,
        height: 22
    });

    $('#addCustomerForm').jqxValidator({
       rules: [
           { input: '#firstNameInput', message: 'First name should be between 2 and 25 characters long!', action: 'blur', rule: 'length=2,25'},
           { input: '#lastNameInput', message: 'Last name should be between 2 and 35 characters long!', action: 'blur', rule: 'length=2,35'},
           { input: '#streetInput', message: 'Must provide an address!', action: 'blur', rule: 'required'},
           { input: '#cityInput', message: 'Must provide a city!', action: 'blur', rule: 'required'},
           { input: '#phoneInput', message: 'Invalid phone number!', action: 'blur', rule: 'phone'},
           { input: '#zipInput', message: 'Invalid zip code!', action: 'blur', rule: function() {
               var zip = $('#zipInput').val();
               return !isNaN(parseInt(zip));
           }},
           { input: '#numAdultsInput', message: 'Must provide a adult count!', action: 'blur', rule: function() {
               var adultCount = $('#numAdultsInput').val();
               return adultCount != "" && adultCount > -1;
           }},
           { input: '#numKidsInput', message: 'Must provide a kid count!', action: 'blur', rule: function() {
               var adultCount = $('#numKidsInput').val();
               return adultCount != "" && adultCount > -1;
           }}
       ]
    });
		
		$('#isAttendee').change(function() {
			var YES = '1';
            $('#serviceRow').show();

			if(this.value === YES) {
				$('#serviceDiv').html(htmlStr);
			} else {
				$('#serviceDiv').html('N/A');
			}
		});
		
		$('#addCustButton').click(function(event) {
			var params = $('#addCustomerForm').serialize();

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