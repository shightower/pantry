var source;

$(document).ready(function() {
	//hide reports section on initial load
	$('#regularReportsGrid').hide();
	
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

    source = {
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

    //action taken when generate button clicked
    $("#generateReportButton").on('click', function () {
        var params = validateDates();

        if(params != null) {
            generateReports(params);
        }
    });

    var dataAdapter = new $.jqx.dataAdapter(source);

    // initialize jqxGrid
    $("#regularReportsGrid").jqxGrid(
        {
            width: 1130,
            source: dataAdapter,
            showstatusbar: true,
            statusbarheight: 50,
            showaggregates: true,
            theme: theme,
            columns: [
                { text: 'Order ID', datafield: 'order_id', width: 100,
                    aggregates: [{'Total Orders': function(aggregatedValue) {
                        return aggregatedValue + 1;
                    }}]},
                { text: 'Customer ID', datafield: 'customer_id', width: 100 },
                { text: 'First Name', datafield: 'firstName', width: 100 },
                { text: 'Last Name', datafield: 'lastName',  width: 120 },
                { text: '# of Adults', datafield: 'numAdults', width: 100, aggregates: ['sum']},
                { text: '# of Kids', datafield: 'numKids', width: 80, aggregates: ['sum']},
                { text: 'Order Weight', datafield: 'weight', width: 115, aggregates: ['sum']},
                { text: '# of Bags', datafield: 'numBags', width: 85, aggregates: ['sum']},
                { text: 'Ethnicity', datafield: 'ethnicity',  width: 175 },
                { text: 'Attendee BCC', datafield: 'isAttendee', columntype: 'checkbox',  width: 150,
                    aggregates: [{ 'Attend BCC':
                        function (aggregatedValue, currentValue) {
                            if (currentValue) {
                                return aggregatedValue + 1;
                            }
                            return aggregatedValue;
                        }
                    },
                        { 'Don\'t Attend BCC':
                            function (aggregatedValue, currentValue) {
                                if (!currentValue) {
                                    return aggregatedValue + 1;
                                }
                                return aggregatedValue;
                            }
                        }]}
            ]
        });
});

function generateReports(params) {
	var paramStr = 'startDate=' + params.startDate + '&';
	paramStr += 'endDate=' + params.endDate + '&';
    paramStr += 'action=generateRegularReport';
    var results;

    $.post('regularReport.php', paramStr, function(results, status) {
        source.localdata = JSON.parse(results);

        $("#regularReportsGrid").jqxGrid('updatebounddata', 'cells');
        $('#regularReportsGrid').show();

    }).fail(function(xhr, status, error) {
        alert('failure. \n' + xhr);
    });

    return results;
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