let lang = localStorage.getItem('lang');
if (lang == null) {
    localStorage.setItem('lang', 'kh');
}

let intVal = function (i) {
    return typeof i === 'string' ?
        i.replace(/[\$,]/g, '') * 1 :
        typeof i === 'number' ?
            i : 0;
};

let intQty = function (i) {
    let num = parseInt(i) | 0;
    return num.toLocaleString();
}


let intQtyFormat = function (i) {
    let num = parseInt(i) | 0;
    return num.toFixed(2);
}

let currency = function (i) {
    let num = parseFloat(i) | 0;
    if (lang === 'en') {
        return '$' + num.toFixed(2);
    }

    return num.toLocaleString() + '៛';
}

let currencyUSD = function (i) {
    let num = parseFloat(i) | 0;
    return '$' + num.toFixed(2);
}


let imageAvatar = function (img) {
    return `<div class="text-center"> <img src="/uploads/` + img + `" width="30px" onerror="this.src='/uploads/none.jpg'"/></div>`;
}

$(document).on('click', 'table img', function () {
    $('#modal-avatar').modal('show');
    $('#modal-avatar img').attr('src', $(this).attr('src'));
});

let active = window.location.pathname.replace('/', '');

let list = $('.sidebar-menu li').removeClass("active");

if (active === 'list_export') {
    active = 'export';
}

if (active === 'list_import') {
    active = 'import';
}

if (active === 'adjustment') {
    active = 'warehouse';
}

if (active === 'list_sale' || active == 'sale_record' || active == 'sale_report' || active == 'stock_report' || active == 'chart_report') {
    active = 'sale';
}

if (active === 'categories' || active == 'brands') {
    active = 'categories';
}

if (active === 'stores') {
    active = 'setting';
}

if (active === 'customers') {
    active = 'users';
}

if (active === 'list_products') {
    active = 'products';
}

$('#' + active).addClass('active');

//iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
})

$('.select2').select2();
$('.textarea').wysihtml5();
$('.date-range-picker').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {format: 'MM/DD/YYYY hh:mm A'},
    }
).val('');


$(document).on('change', 'form input[type=text], select', function () {

    // if ($(this).val().length === 0 && $(this).attr('required') == '') {
    //     $(this).parent().addClass('has-error');
    //     $(this).parent().children('span').addClass('select2-selections')
    // }
    //
    // if ($(this).val().length > 0) {
    //     $(this).parent().removeClass('has-error');
    //     $(this).parent().addClass('has-success');
    // }
});


$(document).on("input", ".numeric", function () {
    this.value = this.value.replace(/\D/g, '');
});


$.extend(true, $.fn.dataTable.defaults, {
    "language": {
        "processing": `
                    <span class='fa-stack fa-lg'>
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>
                       </span>&emsp;Processing ...
<!--                       <img width="100px" src="https://cdn.dribbble.com/users/1787505/screenshots/7300251/media/a351d9e0236c03a539181b95faced9e0.gif"/>-->
`,
        "select": {
            "rows": {
                _: '%d rows selected',
                0: 'Click row to select',
                1: '1 row selected'
            }
        }
    },
    "fnInitComplete": function (oSettings, json) {
        $(".dataTables_length .select2").select2({
            /* select2 options, as an example */
            minimumResultsForSearch: -1
        });
    },
});


let status = function (t) {
    let color = 'success';
    let text = 'Return';

    if (t === 1) {
        color = 'danger';
        text = 'Lost';
    } else if (t === 0) {
        color = 'warning';
        text = 'Broken';
    } else if (t === 'Import') {
        color = 'success';
        text = 'Import';
    } else if (t === 'Export') {
        color = 'danger';
        text = 'Export';
    } else if (t === 'Paid') {
        text = t;
    } else {
        text = t;
    }
    return `<span class="label label-` + color + `">` + text + `</span>`;
}

function isDecimalNumber(key) {
    let keycode = (key.which) ? key.which : key.keycode;
    let parts = key.srcElement.value.split('.');

    if (parts.length > 1 && keycode == 46) {
        return false;
    } else {
        if (keycode == 46 || keycode >= 48 && keycode <= 57)
            return true;
        return false;
    }
}

function isDecimalInt(key) {
    let keycode = (key.which) ? key.which : key.keycode;
    let parts = key.srcElement.value.split('.');

    if (keycode == 46) {
        return false;
    } else {
        if (keycode == 46 || keycode >= 48 && keycode <= 57)
            return true;
        return false;
    }
}
