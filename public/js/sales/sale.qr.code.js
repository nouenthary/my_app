

/**
 * @return mixed function
 */
const ITEMS_KEY = 'items';

let getStorage = function (key = null) {
    return JSON.parse(localStorage.getItem(key))
};

let setStorage = function (key, value) {
    localStorage.setItem(key, JSON.stringify(value));
    getItems();
}

/**
 * @return mixed function
 */

let items = [
    {
        id: 15,
        name: ' ខោខ្លី​ 4500៛',
        image: '2022_10_27_09_55_30.jpg',
        price: 4500,
        qty: 10,
        amount: 14500
    },
    {
        id: 14,
        name: ' អាវ Polo 4500៛',
        image: '2022_10_27_09_53_42.jpg',
        price: 4500,
        qty: 10,
        amount: 14500
    },
    {
        id: 11,
        name: ' អាវសាច់ចាក់ 15000៛',
        image: '2022_10_27_09_07_38.jpg',
        price: 1500,
        qty: 150000,
        amount: 14500
    },
    {
        id: 150,
        name: ' ខោខ្លី​ 4500៛',
        image: '2022_10_27_09_55_30.jpg',
        price: 4500,
        qty: 10,
        amount: 14500
    },
    {
        id: 140,
        name: ' អាវ Polo 4500៛',
        image: '2022_10_27_09_53_42.jpg',
        price: 4500,
        qty: 10,
        amount: 14500
    },
    {
        id: 110,
        name: ' អាវសាច់ចាក់ 15000៛',
        image: '2022_10_27_09_07_38.jpg',
        price: 1500,
        qty: 150000,
        amount: 14500
    }
];

//setStorage(ITEMS_KEY, items);

/**
 * @return mixed
 */

function getItems() {
    let items = getStorage(ITEMS_KEY);
    if (items !== null) {
        $('.list-group').empty();
        items.forEach(function (item) {
            listItems(item);
        });
    }
}

getItems();


/**
 * @return mixed
 */

function listItems(items) {
    $('.list-group').append(
        `
                <li class="list-group-item-sale" id="${items.id}">
                    <div class="item-row-sale">

                        <div style="width: 100px">
                            <img src="/uploads/` + items.image + `"  height="80" width="80" alt="me" this.src='/uploads/none.jpg' style="border-radius: 5px">
                        </div>

                        <div class="list-item-sale">
                            <span> ${product} : ${items.name}</span><br>
                            <span> ${unit_price} : ${currency(items.price)}</span><br>
                            <span> ${total} : ${currency(items.amount)}</span>

                            <div class="input-group text-center" style="align-items: center">
                                        <span class="input-group-addon fa-close-qty"><i class="fa fa-close text-danger"/></span>
                                <input type="number" class="form-control text-center text-primary qty input-sale" value="${items.qty}"  onkeypress="return isDecimalInt(event)">
                                 <span class="input-group-addon fa-minus-qty"><i class="fa fa-minus text-primary"/></span>
                                <span class="input-group-addon fa-plus-qty"><i class="fa fa-plus text-primary"/></span>

                            </div>

                        </div>
                    </div>
                </li>
            `
    );
}

/**
 * @return mixed
 */

$(document).on('click', '.fa-close-qty', function () {
    let id = $(this).closest('li').attr('id');
    let items = getStorage(ITEMS_KEY);
    let data = items.filter(i => parseInt(i.id) !== parseInt(id));
    setStorage(ITEMS_KEY, data);
    getTotal();
});

/**
 * @return mixed
 */
let water_droplet = document.getElementById("water_droplet");
let camera_flashing_2 = document.getElementById("camera_flashing_2");
let door_bell = document.getElementById("door_bell");

$(document).on('click', '.fa-plus-qty', function () {
    water_droplet.play();
    let id = $(this).closest('li').attr('id');
    let items = getStorage(ITEMS_KEY);
    let index = items.findIndex(i => parseInt(i.id) === parseInt(id));
    let qty = parseInt(items[index].qty) + 1;
    let price = parseFloat(items[index].price);
    //check stock
    // let stock = check_stock(id);
    //
    // if (qty > parseInt(stock.quantity)) {
    //     alert('ទំនិញអស់ស្តុក');
    //     getItems();
    //     getTotal();
    //     return;
    // }

    items[index].qty = qty;
    items[index].amount = qty * price;
    setStorage(ITEMS_KEY, items);
    getTotal();
});

/**
 * @return mixed
 */

$(document).on('click', '.fa-minus-qty', function () {
    water_droplet.play();
    let id = $(this).closest('li').attr('id');
    let items = getStorage(ITEMS_KEY);
    let index = items.findIndex(i => parseInt(i.id) === parseInt(id));
    if (parseInt(items[index].qty) > 1) {
        let qty = parseInt(items[index].qty) - 1;
        let price = parseFloat(items[index].price);
        items[index].qty = qty;
        items[index].amount = qty * price;
        setStorage(ITEMS_KEY, items);
        getTotal();
    }
})


/**
 * @return mixed
 */

$(document).on('change cut copy paste', '.qty', function (e) {
    e.preventDefault();
    let id = $(this).closest('li').attr('id');
    let items = getStorage(ITEMS_KEY);
    let index = items.findIndex(i => parseInt(i.id) === parseInt(id));
    let qty = $(this).val();

    if ($(this).val() === '') {
        $(this).val(1);
        qty = 1;
    }

    // check stock
    // let stock = check_stock(id);
    //
    // if (qty > parseInt(stock.quantity)) {
    //     alert('ទំនិញអស់ស្តុក');
    //     getItems();
    //     getTotal();
    //     return;
    // }

    let price = parseFloat(items[index].price);
    items[index].qty = qty;
    items[index].amount = qty * price;
    setStorage(ITEMS_KEY, items);
    getTotal();
});

/**
 * @return mixed
 */

$(document).on('click', '.btn-cancel', function () {

    let confirm1 = confirm('លុបមែនទេ ?')

    if(confirm1){
        localStorage.removeItem(ITEMS_KEY);
        $('.list-group').empty();
        getItems();
        getTotal();
    }
});

/**
 * @return mixed
 */

function searchProduct(arg) {
    $.ajax({
        type: 'GET',
        url: `get_product_by_column?code=${arg}`,
        success: function (resultData) {
            if(resultData == null || resultData === ''){
                alert("Something went wrong");
                return;
            }
            let qty = parseInt(resultData.quantity);
            if (qty === 0) {
                alert("ទំនិញអស់ស្តុក");
                return;
            } else {
                camera_flashing_2.play();
                let items = getStorage(ITEMS_KEY);

                let product = {
                    id: resultData.product_id,
                    name: resultData.name,
                    image: resultData.image,
                    price: resultData.price,
                    qty: 1,
                    amount: resultData.price,
                    code: resultData.code,
                };

                if (items === null) {
                    setStorage(ITEMS_KEY, [product]);
                    getTotal();
                    return;
                } else  {
                    let index = items.findIndex(x => parseInt(x.id) === parseInt(resultData.product_id));
                    if (index > -1) {
                        // let quantity = parseInt(items[index].qty) + 1;
                        // let price = parseFloat(items[index].price);
                        // items[index].qty = quantity;
                        // items[index].amount = quantity * price;
                        //
                        // setTimeout(function () {
                        //     //setStorage(ITEMS_KEY, items);
                        // },1000);
                        // return;
                        getTotal();
                    } else if (index < 0) {
                        setTimeout(function () {
                            setStorage(ITEMS_KEY, [...items, product]);
                            getTotal();
                        },500);

                    }
                }
            }
        }
    });
}

/**
 * @return mixed
 */




let html5QrCode = new Html5Qrcode("reader");
const config = {fps: 15, qrbox: 200};

/**
 * @return mixed
 */

function qrCodeSuccessCallback(successMessage) {
    // $('#result').append(`<h1>${successMessage}</h1>`);
    searchProduct(successMessage);
}

/**
 * @return mixed
 */

function qrCodeFailedCallback(failedMessage) {
}

html5QrCode.start({facingMode: "environment"}, config, qrCodeSuccessCallback, qrCodeFailedCallback);


/**
 * @return mixed
 */

function getTotal() {
    let items = getStorage(ITEMS_KEY);

    let qty = 0;
    let total = 0;
    let length = 0;

    if(items != null){
        items.forEach(function (item) {
            //console.log(item);
            total = total + parseFloat(item.amount);
            qty = qty + parseInt(item.qty);
        });
        length = items.length;
    }
    $('#total-qty').text(`${length} (${qty})`);
    $('#total-price').text(currency(total));
    //console.log(total);
    //console.log(items.length);
}

getTotal();


//
$(document).on('click','.btn-payment',function () {
    let items = getStorage(ITEMS_KEY);
    if(items == null){
        alert('សូមស្កេន');
        return;
    }

    door_bell.play();

    $(this).attr('disabled')

    let total = 0;
    let qty = 0;
    let total_item = items.length;

    for (let i in items) {
        total = total + parseFloat(items[i].amount);
        qty = qty + parseFloat(items[i].qty);
    }
    // console.log(total);
    // console.log(qty);
    // console.log(total_item);
    let data = {
        customer_id: 34,
        customer_name: 'អតិថិជនទូទៅ',
        items,
        _token: $('meta[name=csrf-token]').attr('content'),
        total_item,
        total_quantity: qty,
        total,
        note: '',
        amount_paid: total,
        paid_by: 'cash'
    };
    //console.log(data);
    $.ajax({
        url: "/post_sale",
        data: data,
        type: 'post',
        success: function (data) {
            if (data.error) {
                alert(data.error);
                return;
            }
            localStorage.removeItem(ITEMS_KEY);
            location.href = '/invoice/' + data.sale_id;
        }
    });
});

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



