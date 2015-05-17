$(document).ready(function () {	
								
		$('#addCustButton').jqxButton({
			width: 150,
			theme: 'ui-sunny'
		});

    $('#serviceRow').hide();

    $("#phoneInput").jqxMaskedInput({
        mask: '(###)###-####',
        width: 125,
        height: 22
    });

    $('#zipInput').jqxMaskedInput({
        mask: '#####',
        width: 50,
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
            var checkParams = "";
            checkParams += 'firstName=' + $('#firstNameInput').val() + '&';
            checkParams += 'lastName=' + $('#lastNameInput').val() + '&';
            checkParams += 'phone=' + $('#phoneInput').val() + '&';
            checkParams += 'action=checkForExisting';

            $.get('addCustomerIframe.php', checkParams, function(resp) {
               resp = JSON.parse(resp);

                if(resp.alreadyExists) {
                    $("#ignore-duplicate-confirm").dialog('open');
                } else {
                    addNewCustomer();
                }
            });



		});

    $("#ignore-duplicate-confirm").dialog({
        autoOpen: false,
        resizable: false,
        width: 250,
        height: 300,
        modal: true,
        position: {my: "top", at: "center"},
        show: {effect: "shake", duration: 800},
        buttons: {
            "Yes": function() {
                addNewCustomer();
                $(this).dialog('close');
            },
            "No": function() {
                $(this).dialog('close');
            }
        }
    });

});

function addNewCustomer() {
    var isFormValid = $('#addCustomerForm').jqxValidator('validate');

    if(isFormValid) {
        var params = $('#addCustomerForm').serialize();

        $.post('addCustomer.php', params, function(resp) {
            noty({
                layout: 'center',
                type: 'success',
                text: '<h3>Customer Successfully Added</h3>',
                timeout: 1000,
                callback: {
                    afterClose: function() {
                        location.reload();
                    }
                }

            });

        }).fail(function(xhr, status, error) {
            alert('failure. \n' + xhr);
        });
    }
}