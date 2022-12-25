//
export const pageSize = [
    {key: 10, value: 10},
    {key: 20, value: 20},
    {key: 50, value: 50},
    {key: 100, value: 100},
    {key: 500, value: 'All'},
];
//
export const convertParams = (params) => {
    params = checkNull(params);
    if (params !== '') {
        return Object.entries(params)
            .map(([k, v]) => `${k}=${v}`)
            .join('&');
    }
}
//
export const formatQtyOrCurrency = (number = '0') => {
    return parseFloat(number).toLocaleString();
}
//
export const checkNull = (objects = {}) => {
    Object.keys(objects).forEach((key) => {
        if (objects[key] == null) {
            objects[key] = '';
        }
    });
    return objects;
}


export const isLetterOrNumber = (e) => {
    let char = String.fromCharCode(e.keyCode);
    if (/^[A-Za-z0-9]+$/.test(char)) return true;
    else e.preventDefault();
}
