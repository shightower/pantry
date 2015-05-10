var detailsSource;
var summarySource;

$(document).ready(function() {
	//hide reports section on initial load
	$('#regularReportsSummaryGrid').hide();
    $('#regularReportsDetailsGrid').hide();
    $('#summaryButtonDiv').hide();
    $('#detailsButtonDiv').hide();
	
	//start date Jqx
	$("#startDateSelection").jqxDateTimeInput({
		width: '250px',
		height: '25px',
		formatString: 'dddd MMM dd, yyyy'
	});
	
	//end date Jqx
	$("#endDateSelection").jqxDateTimeInput({
		width: '250px',
		height: '25px',
		formatString: 'dddd MMM dd, yyyy'
	});
	
	//generate button
	$("#generateReportButton").jqxButton({
		width: '200',
		theme: theme
	});

    summarySource = {
        localdata: [],
        datatype: "json",
        datafields: [
            { name: 'totalFamilies', type: 'int'},
            { name: 'totalAdults', type: 'int' },
            { name: 'totalKids', type: 'int' },
            { name: 'totalEthnicities', type: 'int' },
            { name: 'totalBccAttendees', type: 'int' },
            { name: 'totalNonBccAttendees', type: 'int' },
            { name: 'totalWeight', type: 'int' },
            { name: 'totalBags', type: 'int' }
        ]
    };

    detailsSource = {
        localdata: [],
        datatype: "json",
        datafields: [
            { name: 'order_id', type: 'int'},
            { name: 'customer_id', type: 'int'},
            { name: 'firstName', type: 'string' },
            { name: 'lastName', type: 'string' },
            { name: 'numAdults', type: 'int' },
            { name: 'numKids', type: 'int' },
            { name: 'ethnicity', type: 'string' },
            { name: 'isAttendee', type: 'bool' },
            { name: 'weight', type: 'int' },
            { name: 'numBags', type: 'int' }
        ]
    };

    var summaryDataAdapter = new $.jqx.dataAdapter(summarySource);
    var detailsDataAdapter = new $.jqx.dataAdapter(detailsSource);

    // initialize jqxGrid
    $("#regularReportsSummaryGrid").jqxGrid(
        {
            width: 1060,
            source: summaryDataAdapter,
            theme: theme,
            pageable: true,
            autoheight: true,
            sortable: true,
            altrows: true,
            columns: [
                { text: 'Total Families', datafield: 'totalFamilies', width: 120, align: 'center', columngroup: 'reportSummary'},
                { text: 'Total Adults', datafield: 'totalAdults', width: 120, align: 'center',columngroup: 'reportSummary' },
                { text: 'Total Kids', datafield: 'totalKids',  width: 100, align: 'center',columngroup: 'reportSummary' },
                { text: 'Total Ethnicities', datafield: 'totalEthnicities', width: 130, align: 'center',columngroup: 'reportSummary'},
                { text: 'Total Bags', datafield: 'totalBags', width: 100, align: 'center',columngroup: 'reportSummary'},
                { text: 'Total Weight', datafield: 'totalWeight', width: 100, align: 'center',columngroup: 'reportSummary'},
                { text: 'Total BCC Attendees', datafield: 'totalBccAttendees', width: 170, align: 'center',columngroup: 'reportSummary'},
                { text: 'Total Non BCC Attendees', datafield: 'totalNonBccAttendees',  width: 220, align: 'center',columngroup: 'reportSummary' }
            ],
            columngroups: [
                {text: "Regular Orders Summary Report", align: 'center', name: 'reportSummary'}
            ]
        });

    $("#regularReportsDetailsGrid").jqxGrid(
        {
            width: 1100,
            source: detailsDataAdapter,
            theme: theme,
            pageable: true,
            autoheight: true,
            sortable: true,
            altrows: true,
            columns: [
                { text: 'Order ID', datafield: 'order_id', width: 100, columngroup: 'reportDetails'},
                { text: 'First Name', datafield: 'firstName', width: 100, columngroup: 'reportDetails' },
                { text: 'Last Name', datafield: 'lastName',  width: 120, columngroup: 'reportDetails' },
                { text: '# of Adults', datafield: 'numAdults', width: 100, columngroup: 'reportDetails'},
                { text: '# of Kids', datafield: 'numKids', width: 80, columngroup: 'reportDetails'},
                { text: 'Order Weight', datafield: 'weight', width: 115, columngroup: 'reportDetails'},
                { text: '# of Bags', datafield: 'numBags', width: 85, columngroup: 'reportDetails'},
                { text: 'Ethnicity', datafield: 'ethnicity',  width: 250, columngroup: 'reportDetails' },
                { text: 'Attendee BCC', datafield: 'isAttendee', columntype: 'checkbox',  width: 150, columngroup: 'reportDetails'}
            ],
            columngroups: [
                {text: "Regular Orders Detailed Report", align: 'center', name: 'reportDetails'}
            ]
        });

    //action taken when generate button clicked
    $("#generateReportButton").on('click', function () {
        var params = validateDates();

        if(params != null) {
            generateReports(params);
        }
    });

    // summary pdf export button
    $('#exportSummaryButtonPdf').corner('5px');
    $('#exportSummaryButtonPdf').jqxButton({
        width: 100,
        theme: theme
    });
    $("#exportSummaryButtonPdf").click(function() {
        exportSummaryReport('pdf');
    });

    // summary excel export button
    $('#exportSummaryButtonExcel').corner('5px');
    $('#exportSummaryButtonExcel').jqxButton({
        width: 100,
        theme: theme
    });
    $("#exportSummaryButtonExcel").click(function() {
        exportSummaryReport('xls');
    });

    // details pdf export button
    $('#exportDetailsButtonPdf').corner('5px');
    $('#exportDetailsButtonPdf').jqxButton({
        width: 100,
        theme: theme
    });
    $("#exportDetailsButtonPdf").click(function() {
        exportDetailsReport('pdf');
    });

    // details excel export button
    $('#exportDetailsButtonExcel').corner('5px');
    $('#exportDetailsButtonExcel').jqxButton({
        width: 100,
        theme: theme
    });
    $("#exportDetailsButtonExcel").click(function() {
        exportDetailsReport('xls');
    });
});

function generateReports(params) {
	var paramStr = 'startDate=' + params.startDate + '&';
	paramStr += 'endDate=' + params.endDate + '&';
    var detailsParamStr = paramStr + 'action=generateRegularReportDetails';
    var summaryParamStr = paramStr + 'action=generateRegularReportSummary';

    $.post('regularReport.php', summaryParamStr, function(results) {
        summarySource.localdata = JSON.parse(results);

        $("#regularReportsSummaryGrid").jqxGrid('updatebounddata', 'cells');
        $('#regularReportsSummaryGrid').show();
        $('#summaryButtonDiv').show();

    }).fail(function(xhr, status, error) {
        alert('failure. \n' + xhr);
    });

    $.post('regularReport.php', detailsParamStr, function(results) {
        detailsSource.localdata = JSON.parse(results);

        $("#regularReportsDetailsGrid").jqxGrid('updatebounddata', 'cells');
        $('#regularReportsDetailsGrid').show();
        $('#detailsButtonDiv').show();

    }).fail(function(xhr, status, error) {
        alert('failure. \n' + xhr);
    });
}

function validateDates() {
    var startDate = $('#startDateSelection').jqxDateTimeInput('getDate');
    var endDate = $('#endDateSelection').jqxDateTimeInput('getDate');

    //object to hold REST parameters
    var params = new Object();

    if(startDate == null) {
        errorNotification('Please enter a valid starting date');
        params = null;
    } else if(endDate == null) {
        errorNotification('Please enter a valid ending date');
        params = null;
    } else if(startDate > endDate) {
        errorNotification('Ending date must come after Start date');
        params = null;
    } else {
        startDate = startDate.toUTCString();
        endDate = endDate.toUTCString();
        params.startDate = startDate;
        params.endDate = endDate;
    }

    return params;
}

function errorNotification(msg) {
    noty({
        layout: 'center',
        type: 'error',
        text: '<h3>' + msg + '</h3>',
        timeout: 2000
    });
}

function exportSummaryReport(fileType) {
    var summaryFile = 'Cupboard_Generated_Report_Summary';
    $("#regularReportsSummaryGrid").jqxGrid('exportdata', fileType, summaryFile);
}

function exportDetailsReport(fileType) {
    var detailsFile = 'Cupboard_Generated_Report_Details';
    $("#regularReportsDetailsGrid").jqxGrid('exportdata', fileType, detailsFile);
}