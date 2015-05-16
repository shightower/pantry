
var filterCondition = 'contains';
var or_filter_operator = 0;

$(document).ready(function () {
		//initially hide the clear filter div
		$('#clearSearchDiv').hide();
		
		//Round Search
		$('#searchBox').corner('5px');
		
		$('#searchButton').jqxButton({
			width: 100,
			theme: theme
		});
		
		$('#searchBox').keyup(function() {
			applyFilter();
		});

		$('#searchButton').click(function() {
			applyFilter();
		});
		
		//$('#clearButton').jqxButton({
		//	width: 100,
		//	theme: theme
		//});
		
		$('#clearButton').click(function() {
			$("#customersGrid").jqxGrid('removefilter', 'firstName');
			$("#customersGrid").jqxGrid('removefilter', 'lastName');
			$("#customersGrid").jqxGrid('applyfilters');
			$('#searchBox').html('');
			$('#clearSearchDiv').hide();
		});
		
		$('#searchBox').jqxInput({
			placeHolder: 'Search',
			height: 25,
			width: '60%',
			minLength: 1
		});
		
		var source = {
			datatype: "json",
			datafields: [
				{ name: 'id', type: 'int'},
				{ name: 'firstName', type: 'string' },
				{ name: 'lastName', type: 'string' },
				{ name: 'street', type: 'string' },
				{ name: 'city', type: 'string' },
				{ name: 'state', type: 'string' },
				{ name: 'zip', type: 'string' },
				{ name: 'phone', type: 'string' },
				{ name: 'numAdults', type: 'int' },
				{ name: 'numKids', type: 'int' },
				{ name: 'ethnicity', type: 'string' },
				{ name: 'isAttendee', type: 'bool' },
				{ name: 'service', type: 'string' }
			],
			id: 'id',
			url: 'currentCustomers.php?action=getAllCustomers',
            type: 'GET'
		};

		var dataAdapter = new $.jqx.dataAdapter(source, {
			downloadComplete: function (data, status, xhr) {
			},
			loadComplete: function (data) {
			},
			loadError: function (xhr, status, error) {
                //  todo BETTER ERROR HANDLING HERE
				alert('error occurred');
			}
		});


		
		var editRow = -1;
		// initialize jqxGrid
		$("#customersGrid").jqxGrid({
			width: 1010,
			source: dataAdapter,                
			pageable: true,
			autoheight: true,
			sortable: true,
			altrows: true,
			showsortmenuitems: true,
			theme: theme,
			columns: [
			  { text: 'Id', datafield: 'id', hidden: true},
			  { text: 'First Name', datafield: 'firstName', filterable: true, align: 'center', width: 120, pinned: true },
			  { text: 'Last Name', datafield: 'lastName', filterable: true, align: 'center', width: 140, pinned: true },
			  { text: 'Phone Number', datafield: 'phone', align: 'center', width: 125 },
			  { text: 'Street', datafield: 'street', align: 'center', minwidth: 250},
			  { text: 'City', datafield: 'city', align: 'center', width: 125  },
			  { text: 'Adults', datafield: 'numAdults', align: 'center', width: 75, cellsalign: 'center'  },
			  { text: 'Kids', datafield: 'numKids', align: 'center', width: 65, cellsalign: 'center' },
			  { text: 'BCC Attendee', datafield: 'isAttendee', columntype: 'checkbox', align: 'center', width: 110, cellsalign: 'center' }
			]
		});

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

    $('#numAdultsInput').jqxNumberInput({
        width: '110px',
        height: 22,
        min: 0,
        max: 10,
        inputMode: 'simple',
        spinButtons: true,
        decimalDigits: 0
    });

    $('#numKidsInput').jqxNumberInput({
        width: '110px',
        height: 22,
        min: 0,
        max: 10,
        inputMode: 'simple',
        spinButtons: true,
        decimalDigits: 0
    });

    $('#editCustomerForm').jqxValidator({
        rules: [
            { input: '#firstNameInput', message: 'First name should be between 2 and 25 characters long!', action: 'blur,keyup', rule: 'length=2,25'},
            { input: '#lastNameInput', message: 'Last name should be between 2 and 35 characters long!', action: 'blur,keyup', rule: 'length=2,35'},
            { input: '#streetInput', message: 'Must provide an address!', action: 'blur,keyup', rule: 'required'},
            { input: '#cityInput', message: 'Must provide a city!', action: 'blur,keyup', rule: 'required'},
            { input: '#phoneInput', message: 'Invalid phone number!', action: 'blur,keyup', rule: 'phone'},
            { input: '#zipInput', message: 'Invalid zip code!', action: 'blur,keyup', rule: function() {
                var zip = $('#zipInput').val();
                return !isNaN(parseInt(zip) || zip.indexOf('_') == -1);
            }}
        ]
    });
		
		$('#customersGrid').on('rowdoubleclick', function (event)  { 
			editRow = event.args.rowindex;
					
			 // get the clicked row's data and initialize the input fields.
			 var dataRecord = $("#customersGrid").jqxGrid('getrowdata', editRow);
			 $("#id").val(dataRecord.id);
			 $("#firstNameInput").val(dataRecord.firstName);
			 $("#lastNameInput").val(dataRecord.lastName);
			 $("#phoneInput").val(dataRecord.phone);
			 $("#streetInput").val(dataRecord.street);
			 $("#cityInput").val(dataRecord.city);
			 
			 setSelectedIndex('state', dataRecord.state);
			 $("#zipInput").val(dataRecord.zip);
            $("#numAdultsInput").val(dataRecord.numAdults);
            $("#numKidsInput").val(dataRecord.numKids);
			 
			 setSelectedIndex('ethnicity', dataRecord.ethnicity);
			 setSelectedIndex('isAttendee', dataRecord.isAttendee);
			 setSelectedIndex('service', dataRecord.service);
			 
			 // show the popup window.
			 $("#popupWindow").jqxWindow('open');
		});
		
		$('#popupWindow').jqxWindow({
			width: 475,
			height: 650,
			isModal: true,
			autoOpen: false,
			cancelButton: $('#cancelButton'),
			animationType: 'combined',
			theme: theme
		});
		
		$('#popupWindow').on('open', function() {
			$('#firstNameInput').jqxInput('selectAll');
		});
		
		//$('#cancelButton').jqxButton({theme: theme});
		$('#editCustButton').jqxButton({theme: theme});
		
		$('#editCustButton').click(function() {
            var formIsValid = $('#editCustomerForm').jqxValidator('validate');

            if(formIsValid) {
                var params = '';
                params += 'id=' + $('#id').val() + '&';
                params += 'firstName=' + $('#firstNameInput').val() + '&';
                params += 'lastName=' + $('#lastNameInput').val() + '&';
                params += 'phone=' + $('#phoneInput').val() + '&';
                params += 'street=' + $('#streetInput').val() + '&';
                params += 'city=' + $('#cityInput').val() + '&';
                params += 'zip=' + $('#zipInput').val() + '&';
                params += 'state=' + $('#state').val() + '&';
                params += 'numOfAdults=' + $('#numAdultsInput').val() + '&';
                params += 'numOfKids=' + $('#numKidsInput').val() + '&';
                params += 'ethnicity=' + $('#ethnicity').val() + '&';
                params += 'isAttendee=' + $('#isAttendee').val() + '&';
                params += 'service=' + $('#service').val() + '&';
                params += 'note=' + $('#noteInput').val() + '&';
                params += 'action=updateCustomer';

                $.post('currentCustomers.php', params, function(resp) {
                    $('#popupWindow').jqxWindow('close');

                    var n = noty({
                        layout: 'center',
                        type: 'success',
                        text: '<h3>Update Applied Successfully</h3>',
                        timeout: 750,
                        callback: {
                            afterClose: function() {

                                //refresh page, and force manual pull of new data
                                location.reload(true);
                            }
                        }
                    });

                }).fail(function() {
                    var n = noty({
                        layout: 'center',
                        type: 'error',
                        text: '<h3>Unable to Update Customer</h3>',
                        timeout: 5000
                    });
                });
            }
		});

    // export customers to PDF document
    $("#pdfExport").click(function() {
        var today = $.format.date(new Date(), "dd-MM-yyyy");
        var fileName = 'Cupboard_Customers_' + today;
        $("#customersGrid").jqxGrid('exportdata', 'pdf', fileName);
    });

    // export customers to Excel document
    $("#excelExport").click(function() {
        var today = $.format.date(new Date(), "dd-MM-yyyy");
        var fileName = 'Cupboard_Customers_' + today;
        $("#customersGrid").jqxGrid('exportdata', 'xls', fileName);
    });
});

function applyFilter() {
	var lastNameFilterGroup = new $.jqx.filter();
	var firstNameFilterGroup = new $.jqx.filter();
	var searchValue = $('#searchBox').val();
	var firstName = '';
	var lastName = '';
	
	if(searchValue != null && searchValue != "") {
		var names = searchValue.split(' ');
		
		if(names.length == 1) {
			lastName = names[0];
			
			var lastNameFilter = lastNameFilterGroup.createfilter('stringfilter', lastName, filterCondition);
			lastNameFilterGroup.addfilter(or_filter_operator, lastNameFilter);
			$('#customersGrid').jqxGrid('addfilter', 'lastName', lastNameFilterGroup);
			$('#customersGrid').jqxGrid('applyFilters');
		} else {
			firstName = names[0];
			lastName = names[1];

			var lastNameFilter = lastNameFilterGroup.createfilter('stringfilter', lastName, filterCondition);
			lastNameFilterGroup.addfilter(or_filter_operator, lastNameFilter);
			
			var firstNameFilter = firstNameFilterGroup.createfilter('stringfilter', firstName, filterCondition);
			firstNameFilterGroup.addfilter(or_filter_operator, firstNameFilter);
			
			$('#customersGrid').jqxGrid('addfilter', 'firstName', firstNameFilterGroup);
			$('#customersGrid').jqxGrid('applyFilters');
		}
		
		//show the clear filter option
		$('#clearSearchDiv').show();								
	}
}

function setSelectedIndex(id, value) {
	var s = document.getElementById(id);
	
	if(s !== null) {
		s.options[0].selected = true;
	
		for ( var i = 0; i < s.options.length; i++ ) {
			if ( s.options[i].value == value ) {
				s.options[i].selected = true;
				return;
			}
		}
	}
}