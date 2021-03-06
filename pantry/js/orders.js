
var DELETE_URL = 'rest/orders/pending/remove';
var REFRESH_RATE = 120000;

function refresh() {
	window.location.reload(true);
}

setTimeout(refresh, REFRESH_RATE);

$(document).ready(function () {
		// source of pending orders
		var regularSource = {
			datatype: "json",
			datafields: [
				{ name: 'id', type: 'int'},
				{ name: 'orderDate', type: 'date' },
				{ name: 'tefapCount', type: 'int' },
				{ name: 'numBags', type: 'int' },
				{ name: 'orderWeight', type: 'int' },
				{ name: 'type', type: 'string' },
				{ name: 'customerFirstName', type: 'string' },
				{ name: 'customerLastName', type: 'string' }
			],
			id: 'id',
			url: 'pendingOrders.php?action=getPendingOrders&type=regular',
            type: 'GET'
		};

    var tefapSource = {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'int'},
            { name: 'orderDate', type: 'date' },
            { name: 'tefapCount', type: 'int' },
            { name: 'orderWeight', type: 'int' },
            { name: 'type', type: 'string' },
            { name: 'customerFirstName', type: 'string' },
            { name: 'customerLastName', type: 'string' }
        ],
        id: 'id',
        url: 'pendingOrders.php?action=getPendingOrders&type=tefap',
        type: 'GET'
    };
		
		//default height for all input fields
		var defaultHeight = 25;
		
		//standard inputs for regular orders
		$("#firstName").width(100);
		$("#firstName").height(defaultHeight);
		$("#lastName").width(150);
		$("#lastName").height(defaultHeight);		
		$("#orderDate").width(150);
		$("#orderDate").height(defaultHeight);	
		$("#orderWeight").jqxNumberInput({inputMode: 'simeple',  width: 75, height: defaultHeight, min: 0, decimalDigits: 0});
		$("#numBags").jqxNumberInput({inputMode: 'simeple',  width: 75, height: defaultHeight, min: 0, decimalDigits: 0});
		
		//standard inputs for TEFAP orders
		$("#tefapFirstName").width(100);
		$("#tefapFirstName").height(defaultHeight);
		$("#tefapLastName").width(150);
		$("#tefapLastName").height(defaultHeight);		
		$("#tefapDate").width(150);
		$("#tefapDate").height(defaultHeight);		
		$("#tefapWeight").jqxNumberInput({inputMode: 'simeple', width: 75, height: defaultHeight, min: 0, decimalDigits: 0});
		$("#tefapCount").jqxNumberInput({inputMode: 'simeple', width: 75, height: defaultHeight, min: 0, decimalDigits: 0});
		
		
		var regularDataAdapter = new $.jqx.dataAdapter(regularSource, {
			downloadComplete: function (data, status, xhr) {
			},
			loadComplete: function (data) {
			},
			loadError: function (xhr, status, error) {
				alert('error occurred');
			}
		});

    var tefapDataAdapter = new $.jqx.dataAdapter(tefapSource, {
        downloadComplete: function (data, status, xhr) {
        },
        loadComplete: function (data) {
        },
        loadError: function (xhr, status, error) {
            alert('error occurred');
        }
    });

		// initialize pendingOrdersGrids
		$("#pendingRegularOrdersGrid").jqxGrid({
			width: 890,
			source: regularDataAdapter,
			pageable: true,
			autoheight: true,
			sortable: true,
			altrows: true,
			showsortmenuitems: true,
			theme: theme,
			columns: [
			  { text: 'Order #', datafield: 'id', cellsalign: 'center', width: 80},
			  { text: 'First Name', datafield: 'customerFirstName', align: 'center', width: 125},
			  { text: 'Last Name', datafield: 'customerLastName', align: 'center', width: 150},
			  { text: 'Order Date', datafield: 'orderDate', align: 'center', cellsformat: 'ddd M/dd/y hh:mm tt', width: 175},
			  { text: '# Bags', datafield: 'numBags', align: 'center', cellsalign: 'center', width: 60},
			  { text: 'Order Type', datafield: 'type', align: 'center', cellsalign: 'center', width: 100},
			  { text: 'Complete', datafield: 'Complete', columntype: 'button', align: 'center', width: 100, cellsrenderer: function()
				{
					return 'Complete';
				}, buttonclick: function(rowIndex) {
					completeRegularOrder(rowIndex);
				}
			  },
			  { text: 'Remove', datafield: 'Remove', columntype: 'button', align: 'center', width: 100, cellsrenderer: function()
				{
					return 'Remove';
				}, buttonclick: function(rowIndex) {
					var dataRecord = $("#pendingRegularOrdersGrid").jqxGrid('getrowdata', rowIndex);
					
					// prompt user for confirmation before adding new order
					var r = confirm("Remove Order-" + dataRecord.id + "?");
					
					if(r == true) {
						deletePendingOrder(dataRecord.id);
					}
				}
			  }
			]
		});

    $("#pendingTefapOrdersGrid").jqxGrid({
        width: 910,
        source: tefapDataAdapter,
        pageable: true,
        autoheight: true,
        sortable: true,
        altrows: true,
        showsortmenuitems: true,
        theme: theme,
        columns: [
            { text: 'Order #', datafield: 'id', cellsalign: 'center', width: 80},
            { text: 'First Name', datafield: 'customerFirstName', align: 'center', width: 125},
            { text: 'Last Name', datafield: 'customerLastName', align: 'center', width: 125},
            { text: 'Order Date', datafield: 'orderDate', align: 'center', cellsformat: 'ddd M/dd/y hh:mm tt', width: 175},
            { text: 'Tefap Count', datafield: 'tefapCount', align: 'center', cellsalign: 'center', width: 105},
            { text: 'Order Type', datafield: 'type', align: 'center', cellsalign: 'center', width: 100},
            { text: 'Complete', datafield: 'Complete', columntype: 'button', align: 'center', width: 100, cellsrenderer: function()
            {
                return 'Complete';
            }, buttonclick: function(rowIndex) {
                completeTefapOrder(rowIndex);
            }
            },
            { text: 'Remove', datafield: 'Remove', columntype: 'button', align: 'center', width: 100, cellsrenderer: function()
            {
                return 'Remove';
            }, buttonclick: function(rowIndex) {
                var dataRecord = $("#pendingTefapOrdersGrid").jqxGrid('getrowdata', rowIndex);

                // prompt user for confirmation before adding new order
                var r = confirm("Remove Order-" + dataRecord.id + "?");

                if(r == true) {
                    deletePendingOrder(dataRecord.id);
                }
            }
            }
        ]
    });
		
		$('#popupOrder').jqxWindow({
			width: 375,
			height: 400,
			resizable: false,
			isModal: true,
			autoOpen: false,
			cancelButton: $('#cancelOrderButton'),
			animationType: 'combined',
			theme: theme
		});
		
		$('#popupTefap').jqxWindow({
			width: 375,
			height: 400,
			resizable: false,
			isModal: true,
			autoOpen: false,
			cancelButton: $('#cancelTefapButton'),
			animationType: 'combined',
			theme: theme
		});
		
		$('#popupOrder').on('open', function() {
			$('#firstName').jqxInput('selectAll');
		});
		
		$('#popupTefap').on('open', function() {
			$('#tefapFirstName').jqxInput('selectAll');
		});
		
		$('#cancelOrderButton').jqxButton({theme: theme});
		$('#completeOrderButton').jqxButton({theme: theme});
		
		$('#cancelTefapButton').jqxButton({theme: theme});
		$('#completeTefapButton').jqxButton({theme: theme});
		
		$('#completeOrderButton').click(function() {			
			var params = '';
			params += 'id=' + $('#id').val() + '&';
			params += 'orderDate=' + $('#orderDate').val() + '&';
			params += 'orderWeight=' + $('#orderWeight')[0].value + '&';
			params += 'numBags=' + $('#numBags')[0].value + '&';
            params += 'action=completeOrder';
			
			//send update request
            $.post('pendingOrders.php', params, function(resp) {
                $('#popupOrder').jqxWindow('close');
                clearOrderPopup();

                noty({
                    layout: 'center',
                    type: 'success',
                    text: '<h3>Order Completed!</h3>',
                    timeout: 750,
                    callback: {
                        afterClose: function() {

                            //refresh page, and force manual pull of new data
                            location.reload(true);
                        }
                    }
                });
            }).fail(function(xhr, status, error) {
                noty({
                    layout: 'center',
                    type: 'error',
                    text: '<h3>Unable to Complete Order</h3>',
                    timeout: 3000
                });
            });
		});

		$('#completeTefapButton').click(function() {			
			var params = '';
			params += 'id=' + $('#tefapOrderId').val() + '&';
			params += 'orderDate=' + $('#tefapDate').val() + '&';
			params += 'orderWeight=' + $('#tefapWeight')[0].value + '&';
			params += 'tefapCount=' + $('#tefapCount')[0].value+ '&';
            params += 'action=completeOrder';
			
			//send update request
            $.post('pendingOrders.php', params, function(resp) {
                $('#popupOrder').jqxWindow('close');
                clearOrderPopup();

                noty({
                    layout: 'center',
                    type: 'success',
                    text: '<h3>Order Completed!</h3>',
                    timeout: 750,
                    callback: {
                        afterClose: function() {

                            //refresh page, and force manual pull of new data
                            location.reload(true);
                        }
                    }
                });
            }).fail(function(xhr, status, error) {
                noty({
                    layout: 'center',
                    type: 'error',
                    text: '<h3>Unable to Complete Order</h3>',
                    timeout: 3000
                });
            });
		});
		
		// if cancel button clicked, clear any populated fields in the popup window
		$('#cancelOrderButton').click(function() {
			clearOrderPopup();
		});
		
		// if cancel button clicked, clear any populated fields in the popup window
		$('#cancelTefapButton').click(function() {
			clearTefapPopup();
		});
});

function completeRegularOrder(rowIndex) {
	// get the clicked row's data and initialize the input fields.
	 var dataRecord = $("#pendingRegularOrdersGrid").jqxGrid('getrowdata', rowIndex);
    $("#id").val(dataRecord.id);
    $("#firstName").val(dataRecord.customerFirstName);
    $("#lastName").val(dataRecord.customerLastName);
    $("#orderDate").val(formatDate(dataRecord.orderDate));
    $("#numBags").attr({'max' : dataRecord.numBags});

    // show the popup window.
    $("#popupOrder").jqxWindow('open');
}

function completeTefapOrder(rowIndex) {
    // get the clicked row's data and initialize the input fields.
    var dataRecord = $("#pendingTefapOrdersGrid").jqxGrid('getrowdata', rowIndex);
    $("#tefapOrderId").val(dataRecord.id);
    $("#tefapFirstName").val(dataRecord.customerFirstName);
    $("#tefapLastName").val(dataRecord.customerLastName);
    $("#tefapDate").val(formatDate(dataRecord.orderDate));

    // show the popup window.
    $("#popupTefap").jqxWindow('open');
}

function deletePendingOrder(id) {
	var params = 'id=' + id + '&';
    params += 'action=deletePending';

    //send update request
    $.post('pendingOrders.php', params, function(resp) {
        $('#popupOrder').jqxWindow('close');
        clearOrderPopup();

        noty({
            layout: 'center',
            type: 'success',
            text: '<h3>Order Removed!</h3>',
            timeout: 750,
            callback: {
                afterClose: function() {

                    //refresh page, and force manual pull of new data
                    location.reload(true);
                }
            }
        });
    }).fail(function(xhr, status, error) {
        noty({
            layout: 'center',
            type: 'error',
            text: '<h3>Unable to Remove Order</h3>',
            timeout: 1500
        });
    });
}

function clearOrderPopup() {
	$("#id").val('');
	$("#firstName").val('');
	$("#lastName").val('');
	$("#orderDate").val('');
	$("#orderWeight").val('');
	$("#numBags").val('');
}

function clearTefapPopup() {
	$("#tefapOrderNum").val('');
	$("#tefapFirstName").val('');
	$("#tefapLastName").val('');
	$("#tefapDate").val('');
	$("#tefapCount").val('');
	$("#tefapWeight").val('');
}

function formatDate(date) {
	var str = date.getMonth() + 1;
	str += '/' + date.getDate();
	str += '/' + date.getFullYear();
	str += '  ' + date.getHours() % 12 == 0 ? 12 : date.getHours() % 12;
	str += ':' + date.getMinutes();
	
	if(date.getHours() / 12 > 1) {
		str += ' PM';
	} else {
		str += ' AM';
	}
	return str;	
}