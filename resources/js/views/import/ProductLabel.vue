<template>
    <div class="my-page">


        <div class="filter">

            <v-autocomplete
                prepend-icon="mdi-weight"
                v-model="product_id"
                :items="products"
                color="blue-grey lighten-2"
                label="ឈ្មោះទំនិញ"
                item-text="name"
                item-value="id"
                clearable
                @change="onSelection"
                dense
                outlined
                class="col-md-3 col-sm-12"
            >
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

            <template>
                <div>

                    <div>
                        <v-row no-gutters>
                            <v-col
                                v-for="(item, index) in items"
                                :key="index"
                                cols="12"
                                md="2"
                                sm="3"
                                class="pa-2"
                            >
                                <v-card
                                    color="white lighten-5"
                                    class="pa-2"
                                    rounded
                                >
                                    <p class="red--texts">
                                        <v-icon color="primary">mdi-sent</v-icon>
                                        ឈ្មោះ​ : {{item.name}}
                                    </p>

                                    <v-divider></v-divider>

                                    <p class="red--texts">
                                        <v-icon>mdi-cash-usd</v-icon>
                                        តម្លៃ : {{item.price}}
                                    </p>

                                    <v-text-field
                                        label=""
                                        outlined
                                        dense
                                        type="number"
                                        :value="item.label"
                                        style="width: 100%"
                                        @focusin="onFocus(index)"
                                        @change="onChange"
                                        :append-icon="'mdi-close-circle'"
                                        @click:append="remove(item)"
                                    ></v-text-field>

                                    <div style="display: flex; justify-content: space-between">

                                        <VueQRCodeComponent
                                            :text="item.code"
                                            :size="80"
                                            error-level="L">
                                        </VueQRCodeComponent>
                                        <div class="mr-5"></div>
                                        <img :src="item.avatar" alt="" width="80" height="80">
                                    </div>

                                </v-card>
                            </v-col>
                        </v-row>
                    </div>

                </div>


            </template>

            <v-fab-transition>
                <v-btn
                    class="primary"
                    color="#2196F3"
                    fixed
                    bottom
                    right
                    fab
                    onclick="window.print()"
                >
                    <v-icon>mdi-printer</v-icon>
                </v-btn>
            </v-fab-transition>

        </div>

        <v-divider></v-divider>

        <div class="book">

            <template v-for="(item, index) in label">

                <div class="page">

                    <br/>

                    <div style="display: flex; flex-wrap: wrap; justify-content: center">

                        <v-card
                            :color="setColor(i.price)"
                            elevation="0"
                            width="70px"
                            align="center"
                            justify="center"
                            :key="Math.random()"
                            style="margin:3px; border-radius: 0"
                            outlined
                            v-for="(i, iz) in item"
                        >
                            <v-icon size="20" style="color: transparent"> mdi-checkbox-blank-circle-outline</v-icon>
                            <h5 class="justify-center"> K STOCK</h5>

                            <div style="display: flex; justify-content: center">

                                <VueQRCodeComponent
                                    :size="50"
                                    :text="i.code"
                                    bg-color="transparent"
                                    error-level="L">
                                </VueQRCodeComponent>

                                <!--                                <div style="width: 10px"></div>-->
                                <!--                                <img :src="item.avatar" alt="" width="40" height="40">-->
                            </div>

                            <h4 class="justify-center">{{(i.price).toLocaleString()}}៛</h4>
                            <v-divider></v-divider>
                        </v-card>

                    </div>
                </div>

            </template>

        </div>

    </div>
</template>

<script>
    import ProductComponent from "../../components/ProductComponent";
    import VueQRCodeComponent from 'vue-qrcode-component'
    import {mapGetters} from "vuex";
    import {convertParams} from "../../utils";
    import axios from "axios";

    export default {
        name: "ProductLabel",
        components: {ProductComponent, VueQRCodeComponent},
        data: () => ({
            index: 0,
            items: [
                // {
                //     id: 1,
                //     name: 'ABC',
                //     code: 'P4500',
                //     label: 50,
                //     price: 4500,
                //     avatar: 'http://192.168.1.57:8485/uploads//aed2fece770d016beb4eaa80e7fda325.jpg'
                // },
                // {
                //     id: 2,
                //     name: 'MMMM',
                //     code: 'P800',
                //     label: 100,
                //     price: 8000,
                //     avatar: 'http://192.168.1.57:8485/uploads//2022_10_27_08_56_04.jpg'
                // },
                // {
                //     id: 3,
                //     name: 'MMMM',
                //     code: 'P800',
                //     label: 50,
                //     price: 18000,
                //     avatar: 'http://192.168.1.57:8485/uploads//81c14330d3d87ef1ebf00a85de6759da.jpg'
                // },
            ],
            label: [],
            pageSize: 90,
            product_id: ''
        }),
        methods: {
            getProductId(id) {
                console.log(id);
            },
            getLabel() {

                let product = [];

                if (this.items.length > 0) {
                    //console.log(this.items);

                    this.items.forEach((e) => {
                        //console.log(e);

                        for (let i = 1; i <= e.label; i++) {
                            product.push({
                                id: e.id,
                                code: e.code,
                                price: e.price,
                                avatar: e.avatar,
                                name: e.name
                            });
                        }
                    });
                }
                //console.log(product);

                let count = 0;
                let totalRow = [];
                let rowLimit = [];
                let round = 1;

                product.forEach((e) => {

                    rowLimit.push({
                        id: e.id,
                        code: e.code,
                        price: e.price,
                        avatar: e.avatar,
                        name: e.name
                    });

                    count = count + 1;

                    if (count === this.pageSize) {
                        totalRow.push(rowLimit);

                        rowLimit = [];

                        count = 0;
                        round = round + 1;
                    }
                });

                if (rowLimit != null && rowLimit.length > 0) {
                    totalRow.push(rowLimit);
                }

                console.log(totalRow);
                this.label = totalRow;
            },
            setColor(price = 0) {
                if (price === 2300) {
                    return '#26C6DA';
                } else if (price === 7000) {
                    return '#9575CD';
                } else if (price === 8000) {
                    return '#FCE4EC';
                } else if (price === 10000) {
                    return '#FFCC80';
                } else if (price === 15000) {
                    return '#64B5F6';
                } else if (price === 18000) {
                    return '#EC407A';
                } else if (price === 25000) {
                    return '#81C784';
                } else {
                    return '#FFEB3B';
                }
            },
            onChange(e) {
                // console.log(e);
                this.items[this.index].label = e;
                this.setStorage();
                this.getLabel();
            },
            onFocus(i) {
                //console.log(i);
                this.index = i;
            },
            remove(i) {
                this.items = this.items.filter(x => x.id !== i.id);
                this.setStorage();
                this.getLabel();
            },
            async onSelection() {
                //alert(this.product_id);
                const params = convertParams({id: this.product_id});

                await axios
                    .get(`/api/auth/search_product?${params}`)
                    .then(response => {
                        //console.log(response.data);

                        let rowIndex = this.items.findIndex(i => i.id === parseInt(response.data.id));
                        //
                        let item = {
                            id: response.data.id,
                            label: this.pageSize,
                            name: response.data.name,
                            avatar: response.data.image,
                            price: parseFloat(response.data.price),
                            code: response.data.code,
                        };
                        //
                        if (this.items.length === 0) {
                            this.items = [item];
                            this.setStorage();
                            return;
                        }
                        //
                        if (rowIndex < 0) {
                            this.items = [...this.items, item];
                            this.setStorage();
                            return;
                        }
                        //
                        if (rowIndex > -1) {
                            this.items = [...this.items];
                        }
                        this.setStorage();

                    });
                this.getLabel();
            },
            setStorage() {
                this.getLabel();
                localStorage.setItem('label', JSON.stringify(this.label));
                localStorage.setItem('product_item', JSON.stringify(this.items));
            },
            getStorage() {
                let items = JSON.parse(localStorage.getItem('product_item'));
                //console.log(items);
                if (items !== null) {
                    this.items = items;
                }
            }
        },
        mounted() {
            this.getStorage();
            this.getLabel();
        },
        computed: {
            ...mapGetters(['products']),
        },
    }
</script>

<style scoped>

    .page {
        width: 21cm;
        min-height: 29.7cm;
        margin: 1cm auto;
        border: 1px #D3D3D3 solid;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .filter {
        margin: 10px;
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {
        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }

    }
</style>
