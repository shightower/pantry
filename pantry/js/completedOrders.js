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

    $('#exportRegularButtonPdf').corner('5px');

    $('#exportRegularButtonPdf').jqxButton({
        width: 100,
        theme: theme
    });

    $('#exportRegularButtonExcel').corner('5px');

    $('#exportRegularButtonExcel').jqxButton({
        width: 100,
        theme: theme
    });

    $('#exportTefapButtonPdf').corner('5px');

    $('#exportTefapButtonPdf').jqxButton({
        width: 100,
        theme: theme
    });

    $('#exportTefapButtonExcel').corner('5px');

    $('#exportTefapButtonExcel').jqxButton({
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
        // clear regular order grid filters
        $("#completedRegularOrdersGrid").jqxGrid('removefilter', 'customerFirstName');
        $("#completedRegularOrdersGrid").jqxGrid('removefilter', 'customerLastName');
        $("#completedRegularOrdersGrid").jqxGrid('applyfilters');

        // clear Tefap grid filters
        $("#completedTefapOrdersGrid").jqxGrid('removefilter', 'customerFirstName');
        $("#completedTefapOrdersGrid").jqxGrid('removefilter', 'customerLastName');
        $("#completedTefapOrdersGrid").jqxGrid('applyfilters');

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
    var regularSource = {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'int'},
            { name: 'orderDate', type: 'date' },
            { name: 'numBags', type: 'int' },
            { name: 'orderWeight', type: 'int' },
            { name: 'type', type: 'string' },
            { name: 'customerFirstName', type: 'string' },
            { name: 'customerLastName', type: 'string' }
        ],
        id: 'id',
        url: 'completedOrders.php?action=getCompletedOrders&type=regular',
        type: 'GET'
    };

    // source of pending orders
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
        url: 'completedOrders.php?action=getCompletedOrders&type=tefap',
        type: 'GET'
    };

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

    // initialize pendingOrdersGrid
    $("#completedRegularOrdersGrid").jqxGrid({
        width: 810,
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
          { text: 'Order Weight', datafield: 'orderWeight', align: 'center', cellsalign: 'center', width: 120},
            { text: 'Order Type', datafield: 'type', align: 'center', cellsalign: 'center', width: 100}
        ]
    });

    // initialize pendingOrdersGrid
    $("#completedTefapOrdersGrid").jqxGrid({
        width: 830,
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
            { text: 'Order Weight', datafield: 'orderWeight', align: 'center', cellsalign: 'center', width: 120},
            { text: 'Order Type', datafield: 'type', align: 'center', cellsalign: 'center', width: 100}
        ]
    });

    // export customers to PDF document
    $("#exportRegularButtonPdf").click(function() {
        exportRegularOrders('pdf');
    });

    $("#exportRegularButtonExcel").click(function() {
        exportRegularOrders('xls');
    });

    $("#exportTefapButtonPdf").click(function() {
        exportTefapOrders('pdf');
    });

    $("#exportTefapButtonExcel").click(function() {
        exportTefapOrders('xls');
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

            $('#completedRegularOrdersGrid').jqxGrid('addfilter', 'customerLastName', lastNameFilterGroup);
            $('#completedRegularOrdersGrid').jqxGrid('applyFilters');

            $('#completedTefapOrdersGrid').jqxGrid('addfilter', 'customerLastName', lastNameFilterGroup);
            $('#completedTefapOrdersGrid').jqxGrid('applyFilters');
        } else {
            firstName = names[0];
            lastName = names[1];

            var lastNameFilter = lastNameFilterGroup.createfilter('stringfilter', lastName, filterCondition);
            lastNameFilterGroup.addfilter(or_filter_operator, lastNameFilter);

            var firstNameFilter = firstNameFilterGroup.createfilter('stringfilter', firstName, filterCondition);
            firstNameFilterGroup.addfilter(or_filter_operator, firstNameFilter);

            $('#completedRegularOrdersGrid').jqxGrid('addfilter', 'customerFirstName', firstNameFilterGroup);
            $('#completedRegularOrdersGrid').jqxGrid('applyFilters');

            $('#completedTefapOrdersGrid').jqxGrid('addfilter', 'customerFirstName', firstNameFilterGroup);
            $('#completedTefapOrdersGrid').jqxGrid('applyFilters');
        }

        //show the clear filter option
        $('#clearSearchDiv').show();
    }
}

function exportRegularOrders(fileType) {
    var regularOrderFile = 'Cupboard_Completed_Regular_Orders';
    $("#completedRegularOrdersGrid").jqxGrid('exportdata', fileType, regularOrderFile);


}

function exportTefapOrders(fileType) {
    var tefapOrderFile = 'Cupboard_Completed_Tefap_Orders';
    $("#completedTefapOrdersGrid").jqxGrid('exportdata', fileType, tefapOrderFile);
}
