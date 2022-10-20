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

$(function () {

    let key = 'items';

    let lang = localStorage.getItem('lang');
    if (lang == null) {
        localStorage.setItem('lang', 'kh');
    }

    let currency = function (i) {
        let num = parseFloat(i) | 0;
        if (lang === 'en') {
            return '$' + num.toFixed(3);
        }

        return num.toLocaleString() + '៛';
    }

    let getHeight = function () {
        var containerHeight = $('body').height() - 430;
        $('.slimScrollDiv').css('height', containerHeight + 'px');
    }

    getHeight();

    $(window).resize(function () {
        getHeight();
    });

    $(window).keyup(function () {
        getHeight();
    });


    let addRow = function (item) {
        return `
            <tr id="` + item.id + `" data-name="` + item.name + `" data-code="` + item.code + `">
                <td id="t-name">` + item.name + `</td>
                <td id="t-price" class="text-right">` + currency(item.price) + `</td>
                <td id="t-qty">
                    <input value="` + item.qty + `"
                        class="form-control form-control-xs qty text-center" onkeypress="return isDecimalInt(event)"/>
                </td>
                <td id="t-amount" class="text-right">` + currency(item.amount) + `</td>
                <td id="t-remove">
                    <i class="fa fa-trash-o tip pointer pos-remove"
                        title="Remove"></i>
                </td>
            </tr>

        `;
    }

    // stock
    let check_stock = function (id) {

        let res = null;

        $.ajax({
            url: "/count_stock?id=" + id,
            type: 'get',
            async: false,
            success: function (data) {

                if (data.error) {
                    alert(data.error);
                    return;
                }
                res = data;
            }
        });

        return res;
    }

    //add item
    $(document).on('click', '.product-item', function () {

        let id = $(this).attr('id');
        let name = $(this).attr('data-name');
        let price = $(this).attr('data-price');
        let code = $(this).attr('data-code');
        let data = {
            id, name, price, qty: 1, amount: price * 1, code
        };
        // check stock
        let stock = check_stock(id);
        let store = JSON.parse(localStorage.getItem('items'));
        let qty_has = 1;
        if (store != null) {
            let items = store.filter(i => i.id == id);
            if (items.length > 0) {
                qty_has = items[0].qty;
            }
        }

        if (stock.quantity == 0) {
            showModal('ទំនិញអស់ស្តុក');
            return
        }

        if (qty_has == stock.quantity) {
            showModal('ទំនិញអស់ស្តុក');
            return
        }

        let count = JSON.parse(localStorage.getItem(key));

        let arr = [
            data
        ];

        if (count == '' || count == null) {
            localStorage.setItem(key, JSON.stringify(arr));
            getItem();
            return;
        }

        for (let i in count) {
            if (count[i].id == id) {
                let qty = parseInt(count[i].qty) + 1;
                count[i].qty = qty
                count[i].amount = qty * count[i].price;

                arr = [
                    ...count,
                ];

                localStorage.setItem(key, JSON.stringify(arr));
                getItem();
                return;
            }
            if (count[i].id != id) {
                arr = [
                    ...count,
                    data
                ];

                localStorage.setItem(key, JSON.stringify(arr));
                getItem();
            }
        }

    });

    let getItem = function () {
        $('.table-item').empty();
        let qty = 0;
        let total = 0;
        let items = JSON.parse(localStorage.getItem(key));

        if (items == null) {
            return;
        }

        if (items != null) {
            for (let i in items) {
                total = total + items[i].amount;
                qty = qty + items[i].qty;
                $('.table-item').append(addRow(items[i]));
            }
        }
        $('#count').text(`${items.length} (${qty})`);
        $('#total').text(currency(total));
        $('#ts_con').text(currency(0));
        $('#ds_con').text(`${currency(0)} (${currency()})`);
        $('#total-payable').text(currency(total));

        $('#md-qty').text(`${items.length} (${qty})`);
        $('#md-riel').text(currency(total));
        $('#md-usd').text('$' + parseFloat(total / 4000).toFixed(3));
        $('#md-total').text(currency(total));

        $('#md-riel-balance').text('0,00៛');
        $('#md-usd-balance').text('$0.00');

    }
    getItem();

    $(document).on('click', '.pos-remove', function () {
        let id = $(this).closest('tr').attr('id');
        let items = JSON.parse(localStorage.getItem(key));
        let data = items.filter(i => i.id != id);
        localStorage.setItem(key, JSON.stringify(data));
        getItem();
    });


    $(document).on('click', '#reset', function () {
        localStorage.removeItem(key);
        getItem();
    });


    $(document).on('click', '#payment', function () {

        let item = JSON.parse(localStorage.getItem(key));

        if (item == null || item.length < 1) {
            showModal('សូមបញ្ចូលផលិតផល');
            return;
        }

        let qty = 0;
        let total = 0;
        let items = JSON.parse(localStorage.getItem(key));
        if (items != null) {
            for (let i in items) {
                total = total + items[i].amount;
                qty = qty + items[i].qty;
            }
        }

        $('#amount_main').val(parseFloat(total).toFixed(3)).focus();
        $('#quick-payable').text(total);
        $('#modal-payment').modal('show');
        getItem();
    });


    let showModal = function (data) {
        //សូមបញ្ចូលផលិតផល
        $('#modal-title').text(data)
        $('#modal-alert').modal('show');
    }


    $('#add_item').focus();
    $('.select2').select2();

    $(document).on('change', '#customer_id', function () {
        let id = $(this).val();
        localStorage.setItem('customer_id', id);
    });

    let get_customer = function () {
        let customer_id = localStorage.getItem('customer_id');
        if (customer_id != null) {
            $('#customer_id').val(customer_id);
            $('#customer_id').val(customer_id).trigger('change');
        }
    }
    get_customer();


    $(document).on('change', '#amount_main', function () {
        let price = $(this).val();

        let riel = $('#md-riel').text().replace(',', '').replace('៛', '');

        if (price == '') {
            $(this).val(0);
            price = 0;
            $('#md-total').text(currency(price))
        }
        $('#md-total').text(currency(price))


        if (price != parseFloat(riel)) {
            let sum = price - riel;
            $('#md-riel-balance').text(currency(sum));
            $('#md-usd-balance').text('$' + parseFloat(sum / 4000).toFixed(3));
        }

        if (price == parseFloat(riel)) {
            $('#md-riel-balance').text('0,00៛');
            $('#md-usd-balance').text('$0.00');
        }
    });
    //

    $(document).on('click', '.quick-cash', function () {
        let price = $(this).text();
        let riel = $('#md-riel').text().replace(',', '').replace('៛', '');

        let amount = $('#amount_main').val();

        if(parseFloat(riel) == parseFloat(amount)){
            //alert(parseFloat(amount) == parseFloat(riel));
            $('#amount_main').val(parseFloat(price));
            $('#md-total').text(currency(price));
            let balance = parseFloat(price) - parseFloat(riel);
            $('#md-riel-balance').text(currency(balance));
            $('#md-usd-balance').text('$' + parseFloat(balance / 4000).toFixed(3));
            return;
        }

        let total = parseFloat(price) + parseFloat(amount)
        $('#amount_main').val(total);

        $('#md-total').text(currency(total));

        if (total != parseFloat(riel)) {
            let sum = total - riel;
            $('#md-riel-balance').text(currency(sum));
            $('#md-usd-balance').text('$' + parseFloat(sum / 4000).toFixed(3));
        }

        if (total == parseFloat(riel)) {
            $('#md-riel-balance').text('0,00៛');
            $('#md-usd-balance').text('$0.00');
        }

    });

    $(document).on('click', '#clear-cash-notes', function () {
        $('#amount_main').val(0);
        $('#md-riel-balance').text('0,00៛');
        $('#md-usd-balance').text('$0.00');
        $('#md-total').text('0,00៛');
    });

    //
    $(document).on("cut copy paste", ".qty", function (e) {
        e.preventDefault();
    });
    $(document).on('change', '.qty', function () {

        let qty = $(this).val();
        let id = $(this).closest('tr').attr('id');
        let items = JSON.parse(localStorage.getItem(key));

        if (qty == '' || qty == 0) {
            qty = 1
        }

        // check stock
        let stock = check_stock(id);

        if (qty > parseInt(stock.quantity)) {
            showModal('ទំនិញអស់ស្តុក');
            getItem();
            return;
        }

        if (items != null) {
            let i = items.findIndex(i => i.id == id);
            items[i].qty = parseInt(qty);
            let price = items[i].price;
            items[i].amount = qty * price;
        }
        localStorage.setItem(key, JSON.stringify(items));
        getItem();
    });


    //
    //payment
    $('#btn-payment').click(function () {

        let amount_pay = $('#md-riel').text().replace(',', '').replace('៛', '');
        let amount_main = $('#amount_main').val();
        $('#amount_main').focus()
        if (parseFloat(amount_main) >= parseFloat(amount_pay)) {
            $('#btn-payment').attr('disabled', 'disabled');
            let items = JSON.parse(localStorage.getItem(key));

            if(items.length == 0){
                location.reload();
                return;
            }

            let total = 0;
            let qty = 0;
            let total_item = items.length;

            for (let i in items) {
                total = total + parseFloat(items[i].amount);
                qty = qty + parseFloat(items[i].qty);
            }

            let data = {
                customer_id: $('#customer_id').val(),
                customer_name: $("#customer_id option:selected").text(),
                items,
                _token: $('meta[name=csrf-token]').attr('content'),
                total_item,
                total_quantity: qty,
                total,
                note: $('#payment_note').val(),
                amount_paid: $('#amount_main').val(),
                paid_by: $('#paid_by').val()
            };

            $.ajax({
                url: "/post_sale",
                data: data,
                type: 'post',
                success: function (data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    $('#modal-payment').modal('hide');
                    localStorage.removeItem(key);
                    localStorage.removeItem('customer_id');
                    location.href = '/invoice/' + data.sale_id;

                }
            });
        }
    });


    //
    $(document).on('submit', '#form-customer', function (e) {
        e.preventDefault();
        let form = $(this).serializeArray();
        console.log(form);

        $.ajax({
            url: "/create_customer",
            data: form,
            type: 'post',
            success: function (data) {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                $('#modal-customer').modal('hide');
                localStorage.setItem('customer_id', data);
                location.reload();
            }
        });

    });

});
