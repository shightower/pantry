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
            autorowheight: true,
            sortable: true,
            altrows: true,
            columnsheight: 65,
            columns: [
                { text: 'Total<br>Families', datafield: 'totalFamilies', width: 90, align: 'center', columngroup: 'reportSummary', cellsalign: 'center'},
                { text: 'Total<br>Adults', datafield: 'totalAdults', width: 90, align: 'center',columngroup: 'reportSummary', cellsalign: 'center' },
                { text: 'Total<br>Kids', datafield: 'totalKids',  width: 90, align: 'center',columngroup: 'reportSummary', cellsalign: 'center' },
                { text: 'Ethnicity<br>Breakdown', datafield: 'totalEthnicities', width: 300, align: 'center',columngroup: 'reportSummary', cellsalign: 'center'},
                { text: 'Total<br>Bags', datafield: 'totalBags', width: 100, align: 'center',columngroup: 'reportSummary', cellsalign: 'center'},
                { text: 'Total<br>Weight', datafield: 'totalWeight', width: 100, align: 'center',columngroup: 'reportSummary', cellsalign: 'center'},
                { text: 'Total<br>BCC Attendees', datafield: 'totalBccAttendees', width: 140, align: 'center',columngroup: 'reportSummary', cellsalign: 'center'},
                { text: 'Total Non<br>BCC Attendees', datafield: 'totalNonBccAttendees',  width: 150, align: 'center',columngroup: 'reportSummary', cellsalign: 'center' }
            ],
            columngroups: [
                {text: "Regular Orders Summary Report", align: 'center', name: 'reportSummary'}
            ]
        });

    $("#regularReportsDetailsGrid").jqxGrid(
        {
            width: 1095,
            source: detailsDataAdapter,
            theme: theme,
            pageable: true,
            autoheight: true,
            sortable: true,
            columnsheight: 65,
            altrows: true,
            columns: [
                { text: 'Order Id', datafield: 'order_id', width: 90, columngroup: 'reportDetails', cellsalign: 'center'},
                { text: 'First<br>Name', datafield: 'firstName', width: 100, columngroup: 'reportDetails', cellsalign: 'center' },
                { text: 'Last<br>Name', datafield: 'lastName',  width: 120, columngroup: 'reportDetails', cellsalign: 'center' },
                { text: 'Number<br>of Adults', datafield: 'numAdults', width: 100, columngroup: 'reportDetails', cellsalign: 'center'},
                { text: 'Number<br>of Kids', datafield: 'numKids', width: 80, columngroup: 'reportDetails', cellsalign: 'center'},
                { text: 'Order<br>Weight', datafield: 'weight', width: 100, columngroup: 'reportDetails', cellsalign: 'center'},
                { text: 'Number<br>of Bags', datafield: 'numBags', width: 85, columngroup: 'reportDetails', cellsalign: 'center'},
                { text: 'Ethnicity', datafield: 'ethnicity',  width: 300, columngroup: 'reportDetails', cellsalign: 'center' },
                { text: 'Attend<br>Bridgeway', datafield: 'isAttendee', columntype: 'checkbox',  width: 120, columngroup: 'reportDetails', cellsalign: 'center'}
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