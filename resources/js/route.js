import VueRouter from 'vue-router';
import Vue from 'vue';
import Import from './components/import/Import.vue';
import Export from './components/import/Export.vue';
import ImportStock from "./views/import/ImportStock";
import AddStock from "./views/import/AddStock";
import ProductLabel from "./views/import/ProductLabel";
Vue.use(VueRouter);
// api
const API_PREFIX = '/api/auth';
export const GET_WAREHOUSES_STOCK = API_PREFIX + '/get_warehouses_stock';
export const UPDATE_QTY_WAREHOUSES = API_PREFIX + '/update_qty_warehouse';
export const GET_PRODUCTS_IMPORT = API_PREFIX + '/get_products_import';
export const CREATE_IMPORT = `/create_import`;
// ui
const DEFAULT_ROUTE = '/ui';
const LIST_WAREHOUSE = `${DEFAULT_ROUTE}/list_warehouse`;
const IMPORT = `${DEFAULT_ROUTE}/import`;
const EXPORT = `${DEFAULT_ROUTE}/export`;
const IMPORT_STOCK = `${DEFAULT_ROUTE}/import_stock`;
const ADD_STOCK = `${DEFAULT_ROUTE}/add_stock`;
const PRODUCT_LABEL = `${DEFAULT_ROUTE}/product_label`;

const routes = [
    {
        path: DEFAULT_ROUTE,
        component: Import
    },
    {
        path: LIST_WAREHOUSE,
        component: Import
    },
    {
        path: IMPORT,
        component: Export
    },
    {
        path: EXPORT,
        component: Export
    },
    {
        path: IMPORT_STOCK,
        component: ImportStock
    },
    {
        path: ADD_STOCK,
        component: AddStock
    },
    {
        path: PRODUCT_LABEL,
        component: ProductLabel
    },

];

export const router = new VueRouter({
    routes,
    mode: 'history',
});
