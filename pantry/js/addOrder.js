var REGULAR_ORDER_TYPE = "Regular";
var TEFAP_ORDER_TYPE = "Tefap";

var lastNameFilterGroup = new $.jqx.filter();
var firstNameFilterGroup = new $.jqx.filter();
var filterCondition = 'contains';
var or_filter_operator = 1;
var selectedRow = -1;
var recordId = -1;
var addTefap = false;

$(document).ready(function () {
		//initially hide the clear filter div
		$('#clearSearchDiv').hide();
		
		//Round Search
		$('#searchBox').corner('5px');
		
		$('#searchButton').jqxButton({
			width: 100,
			theme: theme
		});

		$('#searchButton').click(function() {
			var searchValue = $('#searchBox').val();
			var firstName = '';
			var lastName = '';
			
			if(searchValue != null && searchValue != "") {
				var names = searchValue.split(' ');
				
				if(names.length == 1) {
					lastName = names[0];
					
					var lastNameFilter = lastNameFilterGroup.createfilter('stringfilter', lastName, filterCondition);
					lastNameFilterGroup.addfilter(or_filter_operator, lastNameFilter);
					$('#addOrderGrid').jqxGrid('addfilter', 'lastName', lastNameFilterGroup);
					$('#addOrderGrid').jqxGrid('applyFilters');
				} else {
					firstName = names[0];
					lastName = names[1];

					var lastNameFilter = lastNameFilterGroup.createfilter('stringfilter', lastName, filterCondition);
					lastNameFilterGroup.addfilter(or_filter_operator, lastNameFilter);
					
					var firstNameFilter = firstNameFilterGroup.createfilter('stringfilter', firstName, filterCondition);
					firstNameFilterGroup.addfilter(or_filter_operator, firstNameFilter);
					
					$('#addOrderGrid').jqxGrid('addfilter', 'firstName', firstNameFilterGroup);
					$('#addOrderGrid').jqxGrid('applyFilters');
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
		});
		
		$('#clearButton').jqxButton({
			width: 100,
			theme: theme
		});
		
		$('#clearButton').click(function() {
			$("#addOrderGrid").jqxGrid('removefilter', 'firstName');
			$("#addOrderGrid").jqxGrid('removefilter', 'lastName');
			$("#addOrderGrid").jqxGrid('applyfilters');
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
				{ name: 'phone', type: 'string' },
                { name: 'lastOrderDate', type: 'date' },
				{ name: 'nextAvailableDate', type: 'date' }
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
				alert('error occurred');
			}
		});
		
		var selectedCustomer = -1;
		// initialize jqxGrid
		$("#addOrderGrid").jqxGrid({
			width: 860,
			source: dataAdapter,                
			pageable: true,
			autoheight: true,
			sortable: true,
			altrows: true,
			theme: theme,
			columns: [
			  { text: 'Order Number', datafield: 'id', hidden: true},
			  { text: 'First Name', datafield: 'firstName', filterable: true, align: 'center', width: 120 },
			  { text: 'Last Name', datafield: 'lastName', filterable: true, align: 'center', width: 145 },
			  { text: 'Phone Number', datafield: 'phone', align: 'center', width: 125 },
              { text: 'Last Order Date', datafield: 'lastOrderDate', align: 'center', width: 150, cellsalign: 'center', cellsformat: 'ddd M/dd/y'},
			  { text: 'Next Available Order Date', datafield: 'nextAvailableDate', align: 'center', width: 200, cellsalign: 'center', cellsformat: 'ddd M/dd/y'},
			  { text: 'Create Order', datafield: 'New Order', columntype: 'button', width: 120, cellsrenderer: function()
				{
					return 'New Order';
				}, buttonclick: function(row) {
                  selectedRow = row;
                  $("#new-order-confirm").dialog("open");
				}
			  }
			]
		});

    $("#new-order-confirm").dialog({
        autoOpen: false,
        resizable: false,
        height: 160,
        modal: true,
        show: {effect: "clip", duration: 400},
        position: {my: "top", at: "center"},
        buttons: {
            "Yes": function() {
                addTefap = $("#addTefap").prop('checked');
                var dataRecord = $("#addOrderGrid").jqxGrid('getrowdata', selectedRow);
                recordId = dataRecord.id;
                if(!beforeNextAvailableDate(dataRecord.nextAvailableDate)) {
                    //ajax call to create new order
                    submitNewOrder(recordId, REGULAR_ORDER_TYPE);

                    if(addTefap) {
                        submitNewOrder(recordId, TEFAP_ORDER_TYPE);
                    }

                    resetRecordInfo();
                    $(this).dialog('close');
                } else {
                    $("#order-override-confirm").dialog('open');
                    $(this).dialog('close');
                }
            },
            "No": function() {
                resetRecordInfo();
                $(this).dialog('close');
            }
        }
    });

    $("#order-override-confirm").dialog({
        autoOpen: false,
        resizable: false,
        width: 250,
        height: 300,
        modal: true,
        position: {my: "top", at: "center"},
        show: {effect: "shake", duration: 800},
        buttons: {
            "Yes": function() {
                //ajax call to create new order
                submitNewOrder(recordId, REGULAR_ORDER_TYPE);

                if(addTefap) {
                    submitNewOrder(recordId, TEFAP_ORDER_TYPE);
                }

                resetRecordInfo();
                $(this).dialog('close');
            },
            "No": function() {
                resetRecordInfo();
                $(this).dialog('close');
            }
        }
    });
});

function beforeNextAvailableDate(nextAvailableDate) {
	//check to make sure the customer is not attempting an order before their
	//next available date
	if(nextAvailableDate == null) {
		return false;
	} else {
		//zero out minutes and seconds for dates as we only care about the month and day
		nextAvailableDate = compactDate(nextAvailableDate);
		var currentDate = compactDate(new Date());
		
		if(currentDate.getDay() != 0 && nextAvailableDate < currentDate) {
			return true;
		} else {
            return false;
        }
	}
}

function submitNewOrder(customerId, orderType) {
	
	var params = 'customerId=' + customerId + '&';
    params += 'type=' + orderType.toLowerCase() + '&';
    params += 'action=addOrder';

    //send update request
    $.post('addOrder.php', params, function(resp) {
        noty({
            layout: 'center',
            type: 'success',
            text: '<h3>New ' + orderType + ' Order Adding to Pending List</h3>',
            timeout: 1250
        });
    }).fail(function(xhr, status, error) {
        var text = '<h3>Unable to Add Order</h3>';
        text += 'Reason: ';
        text += xhr.statusText;

        var n = noty({
            layout: 'center',
            type: 'error',
            text: text,
            timeout: 3000
        });
    });
}

function compactDate(date) {
	date.setHours(0);
	date.setMinutes(0);
	date.setSeconds(0);
	date.setMilliseconds(0);
	return date;
}

function resetRecordInfo() {
    recordId = -1;
    selectedRow = -1;
}