$(document).ready(function() {
    //hide reports section on initial load
    $('#reportSummaryDiv').hide();

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

    //action taken when generate button clicked
    $("#generateReportButton").on('click', function () {
        var params = validateDates();

        if(params != null) {
            generateReports(params);
        }
    });
});

function generateReports(params) {
    var paramStr = 'startDate=' + params.startDate + '&';
    paramStr += 'endDate=' + params.endDate + '&';
    paramStr += 'action=generateTefapReport';

    $.post('regularReport.php', paramStr, function(results, status) {
        var data = JSON.parse(results);

        //show div containing reports summary
        $('#totalFamilies').html(data.totalFamilies);
        $('#tefapCount').html(data.tefapCount);
        $('#totalWeight').html(data.totalWeight + ' lbs');
        $('#totalAdults').html( data.totalAdults);
        $('#totalKids').html(data.totalKids);
        //$('#totalBccAttendees').html(data.totalBccAttendees);
        //$('#totalNonBccAttendees').html(data.totalNonBccAttendees);
        $('#totalBccAttendees').html('0');
        $('#totalNonBccAttendees').html('0');
        $('#reportSummaryDiv').show();
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