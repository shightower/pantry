$(document).ready(function () {
		// source of pending orders
		var source = {
			datatype: "json",
			datafields: [
				{ name: 'id', type: 'int'},
				{ name: 'orderDate', type: 'date' },
				{ name: 'tefapCount', type: 'int' },
				{ name: 'numBags', type: 'int' },
				{ name: 'orderWeight', type: 'int' },
				{ name: 'type', type: 'string' },
				{ name: 'customerFirstName', type: 'string' },
				{ name: 'customerLastName', type: 'string' },
				{ name: 'tefap', type: 'bool' }
			],
			id: 'id',
			url: 'completedOrders.php',
            type: 'POST'
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
		
		var editRow = -1;
		// initialize pendingOrdersGrid
		$("#completedOrdersGrid").jqxGrid({
			width: 690,
			source: dataAdapter,                
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
			  { text: 'Order Type', datafield: 'type', align: 'center', cellsalign: 'center', width: 100}
			]
		});
});
