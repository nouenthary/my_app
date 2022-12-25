<template >
    <v-card class="elevation-0">
        <v-card-title style="padding: 0">
            <v-icon class="primary--text">mdi-shopping</v-icon>
            <h3 class="primary--text font-weight-medium">បញ្ជីផលិតផលឃ្លាំង</h3>
        </v-card-title>

        <template v-if="error">
                   <v-alert dense outlined type="error">
                ការទាញយកទិន្នន័យមានបញ្ហា
            </v-alert>
        </template>

        <div>
            <v-row>
                <v-col cols="12" sm="4" md="3">

                    <v-autocomplete default="" v-model="searchable.store_id" :items="store" clearable
                        :loading="isLoading" item-text="name" item-value="id" label="សាខា"
                        @change="get_data()">
                    </v-autocomplete>

                        <!-- outlined -->
                </v-col>

                <v-col cols="12" sm="4" md="3">

                    <v-autocomplete v-model="searchable.product_id" :loading="isLoading" :disabled="isUpdating"
                        :items="products" default="" color="blue-grey lighten-2" label="ឈ្មោះទំនិញ" item-text="name"
                        item-value="id" clearable @change="get_data()">
                        <template v-slot:selection="data">
                            <v-chip v-bind="data.attrs" :input-value="data.selected" close @click="data.select">
                                <v-avatar left>
                                    <v-img :src="data.item.image"></v-img>
                                </v-avatar>
                                {{ data.item.name }}
                            </v-chip>
                        </template>
                        <template v-slot:item="data">
                            <template v-if="typeof data.item !== 'object'">
                                <v-list-item-content v-text="data.item"></v-list-item-content>
                            </template>
                            <template v-else>
                                <v-list-item-avatar>
                                    <img :src="data.item.image" alt="home">
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title v-html="data.item.name"></v-list-item-title>
                                    <v-list-item-subtitle v-html="data.item.group"></v-list-item-subtitle>
                                </v-list-item-content>
                            </template>
                        </template>
                    </v-autocomplete>

                </v-col>

            </v-row>
        </div>


        <v-card elevation="0">
            <v-simple-table
                class="elevation-0 t-borders"
                fixed-header
                dense
            >
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th width="90px">រូបភាព</th>
                            <th class="text-left">
                                ឈ្មោះទំនិញ
                            </th>
                            <th class="text-left">
                                តំលៃ​លក់
                            </th>
                            <th class="text-left">
                                ចំនួនក្នុងឃ្លាំង
                            </th>
                            <th class="text-left">
                                នៅសាខា
                            </th>

                            <th class="text-left" width="80px">
                                សកម្មភាព
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data" :key="item.id">

                            <td id="item.proudct_name">
                                <v-avatar rounded size="35px">
                                    <img :src="item.image" :alt="item.image">
                                </v-avatar>
                            </td>
                            <td>{{ item.proudct_name }}</td>
                            <td class="text-right">{{
                                    formatQtyOrCurrencyCall(item.price)
                            }}៛</td>
                            <td class="text-right">{{ formatQtyOrCurrencyCall(item.quantity) }}</td>
                            <td>{{ item.store_name }}</td>
                            <td class="text-center">
                                <v-btn class="mx-2" fab dark x-small color="primary" @click="editItem(item)">
                                    <v-icon dark>
                                        mdi-pencil
                                    </v-icon>
                                </v-btn>

                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th width="90px"></th>
                            <th class="text-left">
                                សរុប
                            </th>
                            <th class="text-right">

                            </th>
                            <th class="text-right">
                                {{ formatQtyOrCurrencyCall(qty) }}
                            </th>
                            <th class="text-left">
                            </th>
                            <th class="text-left">
                            </th>
                        </tr>
                    </tfoot>
                </template>
            </v-simple-table>
        </v-card>

        <v-row>
            <v-col class="d-flex" cols="12" sm="12" md="1">
                <v-select v-model="searchable.page_size" :items="pageSize" item-value="key" item-text="value"
                    v-on:change="get_data()"></v-select>
            </v-col>
            <v-col class="d-flex" cols="12" sm="12" md="11">
                <div class="text-centers">
                    <v-pagination v-model="searchable.page" :length="total" class="my-2" :total-visible="10"
                        prev-icon="mdi-menu-left" next-icon="mdi-menu-right" @input="get_data()"
                        color="primary"></v-pagination>
                </div>
            </v-col>
        </v-row>
        <!-- {{ searchable.page_size }} -->

        <v-row justify="center">
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-card>

                        <v-toolbar color="primary" dark>
                            <v-icon class="mr-2">
                                mdi-apps
                            </v-icon>
                            កែសម្រួលក្នុងឃ្លាំង

                            <v-btn icon dark @click="dialog = false" style="float:right">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>

                        </v-toolbar>

                        <v-card-text>
                            <v-container>

                                <v-avatar rounded size="150">

                                    <v-img lazy-src="https://picsum.photos/id/11/10/6" :src="items.image"></v-img>

                                </v-avatar>

                                <br />
                                <br />

                                <v-text-field v-model="items.id" outlined label="" required value="0"
                                    style="display:none"></v-text-field>

                                <v-text-field v-model="items.proudct_name" type="text" outlined label="ឈ្មោះទំនិញ"
                                    required :rules="nameRules" v-on:change="onChange"></v-text-field>

                                <v-text-field v-model="items.price" type="number" outlined label="តំលៃ​លក់" required
                                    :rules="priceRules" v-on:change="onChange"></v-text-field>

                                <v-text-field v-model="items.quantity" type="number" outlined label="ចំនួនក្នុងឃ្លាំង"
                                    required :rules="qtyRules" v-on:change="onChange"></v-text-field>

                            </v-container>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="default " @click="dialog = false">
                                <v-icon small class="mr-2">
                                    mdi-close
                                </v-icon>
                                <span class="text-size">បោះបង់</span>

                            </v-btn>
                            <v-btn color="primary white--text" @click="submit" :disabled="disabled"
                                :loading="loading">
                                <v-icon small class="mr-2">
                                    mdi-import
                                </v-icon>
                                <span class="text-size">រក្សាទុក</span>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-form>
            </v-dialog>
        </v-row>

    </v-card>
</template>

<script>

import { mapGetters } from 'vuex';
import { convertParams, pageSize, formatQtyOrCurrency } from "../../utils";
import {GET_WAREHOUSES_STOCK, UPDATE_QTY_WAREHOUSES} from "../../route";
export default {
    name: 'Import',
    data() {
        return {
            pageSize: pageSize,
            searchable: {
                page: 1,
                page_size: 10,
                store_id: '',
                product_id: '',
            },
            isLoading: false,
            isUpdating: false,
            data: [],
            total: 1,
            qty: 0,
            dialog: false,
            disabled: false,
            items: {
                id: 0,
                quantity: 0,
                price: 0,
                proudct_name: '',
                image: ''
            },
            valid: true,
            nameRules: [
                v => !!v || 'សូមបញ្ចូលឈ្មោះផលឺតផល',
            ],
            priceRules: [
                v => !!v || 'សូមបញ្ចូលតម្លៃ',
            ],
            qtyRules: [
                v => !!v || 'សូមបញ្ចូលចំនួន',
            ],
            loading: false,
            error: false
        }
    },
    methods: {
        async get_data() {

            this.isLoading = true;

            if (this.searchable.store_id == null) {
                this.searchable.store_id = '';
            }
            if (this.searchable.product_id == null) {
                this.searchable.product_id = '';
            }
            if (this.searchable.page == null) {
                this.searchable.page = 1;
            }
            const params = convertParams(this.searchable);

            await axios
                .get(`${GET_WAREHOUSES_STOCK}?${params}`)
                .then(response => {
                    //console.log(response.data.data[0]);
                    this.data = response.data.data;
                    this.total = Math.ceil(response.data.total / this.searchable.page_size);
                    this.isLoading = false;
                    let qty = 0;
                    response.data.data.forEach((item) => {
                        qty = qty + parseFloat(item.quantity);
                    });
                    this.qty = qty;
                    this.error = false;
                }).catch(() => {
                    this.error = true;
                });
        },
        formatQtyOrCurrencyCall: (value) => formatQtyOrCurrency(value),
        editItem(item) {
            this.items = item;
            this.dialog = true;
        },
        async submit() {
            this.disabled = true;
            this.loading = true;

            this.$refs.form.validate();
            const data = {
                id: this.items.id,
                name: this.items.proudct_name,
                price: this.items.price,
                quantity: this.items.quantity
            };

            await axios.post(UPDATE_QTY_WAREHOUSES, data)
                .then(response => {
                    if (response.data.message) {
                        this.dialog = false;
                        this.disabled = false;
                        this.loading = false;
                    }
                }).catch(() => {
                    this.disabled = false;
                    this.loading = false;
                });
        },
        onChange(value) {
            if (value == '') {
                this.disabled = true;
            } else {
                this.disabled = false;
            }

        }
    },
    mounted() {
        this.get_data();
    },
    computed: {
        ...mapGetters(['store', 'products']),
    },
}
</script>

