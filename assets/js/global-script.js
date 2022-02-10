//======================== notification ==============================//
function swalAlert(text, icon) {
    Swal.fire({
        icon: icon,
        title: '',
        text: text
    })
}

function smkAlert(msg, type) {
    $.smkAlert({
        text: msg,
        type: type
    });
}

function blockUI(msg) {
    $.blockUI({
        message: '<h3><i class="fas fa-spinner fa-spin fa-1x"></i> ' + msg + '</h3>',
        baseZ: 2000
    });
}

function unblockUI() {
    $.unblockUI();
}



//======================== id formatter ==========================//
function DataFilter(rawStr) {
    var newStr = rawStr.replace('%', '').replace('?', '').replace('+', '').replace(';', '').replace('E', '');
    var pid;

    if (newStr.length == 8) {
        pid = newStr
    } else if (newStr.length > 6) {
        pid = newStr.substr(0, 6);
    } else {
        pid = newStr;
    }

    return pid;
}


//============================ data table ========================//
function rowFormat(value, row, index) {
    return 1 + index;
}

function dateFormat(data) {
    return (moment(data).isValid() && moment(data).format('YYYY') != '1900') ? moment(data).format('DD-MMM-YY') : '';
}

function dateTimeFormat(data) {
    return (moment(data).isValid() && moment(data).format('YYYY') != '1900') ? moment(data).format('DD-MMM-YY h:mm:ss A') : '';
}

function timeFormat(data) {
    return moment(data).format('h:mm:ss A');
}

function badgeValFormat(data) {
    return '<span class="badge badge-secondary">' + data + '</span>';
}

function useType(data) {
    var badgeClass = 'badge-warning';

    if (data === 'Work') {
        badgeClass = 'badge-success'
    }

    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
}

function documents(data) {
    return 'TK' + data;
}


function printTmpl(data) {
    return [
        '<a class="btn btn-warning btn-sm btn-print" href="javascript:void(0)" title="re-print" data-unique-id="', data, '">',
        '<i class="fas fa-print"></i> Re-Print',
        '</a>'
    ].join('');
}