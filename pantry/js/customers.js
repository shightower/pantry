
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
		
		$('#clearButton').jqxButton({
			width: 100,
			theme: theme
		});
		
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
			url: 'customers.php'
		};
		
		//set width and height for popup editor
		var defaultHeight = 25;
		$("#firstName").width(100);
		$("#firstName").height(defaultHeight);
		$("#lastName").width(150);
		$("#lastName").height(defaultHeight);		
		$("#street").width(250);
		$("#street").height(defaultHeight);
		$("#city").width(100);
		$("#city").height(defaultHeight);
		$("#state").width(125);
		$("#state").height(defaultHeight);
		$("#zip").width(75);
		$("#zip").height(defaultHeight);
		$("#phone").width(100);
		$("#phone").height(defaultHeight);
		$("#ethnicity").width(150);
		$("#ethnicity").height(defaultHeight);
		$("#isAttendee").width(75);
		$("#isAttendee").height(defaultHeight);
		$("#service").width(75);
		$("#service").height(defaultHeight);
		
		$("#numAdults").jqxNumberInput({inputMode: 'simple', spinMode: 'simple', width: 50, height: defaultHeight, min: 0, decimalDigits: 0, spinButtons: true });
		$("#numKids").jqxNumberInput({inputMode: 'simple', spinMode: 'simple', width: 50, height: defaultHeight, min: 0, decimalDigits: 0, spinButtons: true });
		
		
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
		
		$('#customersGrid').on('rowdoubleclick', function (event)  { 
			editRow = event.args.rowindex;
					
			 // get the clicked row's data and initialize the input fields.
			 var dataRecord = $("#customersGrid").jqxGrid('getrowdata', editRow);
			 $("#id").val(dataRecord.id);
			 $("#firstName").val(dataRecord.firstName);
			 $("#lastName").val(dataRecord.lastName);
			 $("#phone").val(dataRecord.phone);
			 $("#street").val(dataRecord.street);
			 $("#city").val(dataRecord.city);
			 
			 setSelectedIndex('state', dataRecord.state);
			 //$("#state").val(dataRecord.state);
			 $("#zip").val(dataRecord.zip);
			 $("#numAdults").jqxNumberInput({ decimal: dataRecord.numAdults });
			 $("#numKids").jqxNumberInput({ decimal: dataRecord.numKids });
			 
			 setSelectedIndex('ethnicity', dataRecord.ethnicity);
			 //$("#ethnicity").val(dataRecord.ethnicity);
			 
			 setSelectedIndex('isAttendee', dataRecord.isAttendee);
			 //$("#isAttendee").val(dataRecord.isAttendee);
			 
			 setSelectedIndex('service', dataRecord.service);
			 //$("#service").val(dataRecord.service);
			 
			 // show the popup window.
			 $("#popupWindow").jqxWindow('open');
		});
		
		$('#popupWindow').jqxWindow({
			width: 400,
			height: 650,
			resizable: false,
			isModal: true,
			autoOpen: false,
			cancelButton: $('#cancelButton'),
			animationType: 'combined',
			theme: theme
		});
		
		$('#popupWindow').on('open', function() {
			$('#firstName').jqxInput('selectAll');
		});
		
		$('#cancelButton').jqxButton({theme: theme});
		$('#saveButton').jqxButton({theme: theme});
		
		$('#saveButton').click(function() {			
			var params = '';
			params += 'id=' + $('#id').val() + '&';
			params += 'firstName=' + $('#firstName').val() + '&';
			params += 'lastName=' + $('#lastName').val() + '&';
			params += 'phone=' + $('#phone').val() + '&';
			params += 'street=' + $('#street').val() + '&';
			params += 'city=' + $('#city').val() + '&';
			params += 'zip=' + $('#zip').val() + '&';
			params += 'state=' + $('#state').val() + '&';
			params += 'numAdults=' + $('#numAdults').val() + '&';
			params += 'numKids=' + $('#numKids').val() + '&';
			params += 'ethnicity=' + $('#ethnicity').val() + '&';
			params += 'isAttendee=' + $('#isAttendee').val() + '&';
			params += 'service=' + $('#service').val() + '&';
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
	} else {
		var n = noty({
				layout: 'center',
				type: 'error', 
				text: '<h3>Provide a Search Value</h3>',
				timeout: 2500
			});
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