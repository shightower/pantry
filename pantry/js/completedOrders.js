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
        $("#completedOrdersGrid").jqxGrid('removefilter', 'firstName');
        $("#completedOrdersGrid").jqxGrid('removefilter', 'lastName');
        $("#completedOrdersGrid").jqxGrid('applyfilters');
        $('#searchBox').html('');
        $('#clearSearchDiv').hide();
    });

    $('#searchBox').jqxInput({
        placeHolder: 'Search',
        height: 25,
        width: '60%',
        minLength: 1
    });

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

    // export customers to PDF document
    $("#pdfExport").click(function() {
        var fileName = 'Cupboard_Completed_Orders';
        $("#completedOrdersGrid").jqxGrid('exportdata', 'pdf', fileName);
    });

    // export customers to Excel document
    $("#excelExport").click(function() {
        var fileName = 'Cupboard_Customers';
        $("#completedOrdersGrid").jqxGrid('exportdata', 'xls', fileName);
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
            $('#completedOrdersGrid').jqxGrid('addfilter', 'customerLastName', lastNameFilterGroup);
            $('#completedOrdersGrid').jqxGrid('applyFilters');
        } else {
            firstName = names[0];
            lastName = names[1];

            var lastNameFilter = lastNameFilterGroup.createfilter('stringfilter', lastName, filterCondition);
            lastNameFilterGroup.addfilter(or_filter_operator, lastNameFilter);

            var firstNameFilter = firstNameFilterGroup.createfilter('stringfilter', firstName, filterCondition);
            firstNameFilterGroup.addfilter(or_filter_operator, firstNameFilter);

            $('#completedOrdersGrid').jqxGrid('addfilter', 'customerFirstName', firstNameFilterGroup);
            $('#completedOrdersGrid').jqxGrid('applyFilters');
        }

        //show the clear filter option
        $('#clearSearchDiv').show();
    }
}
