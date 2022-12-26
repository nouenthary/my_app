<template>

    <div>



        <v-card outlined elevation="elevation-1" style="margin: 5px">

<!--            <v-card-subtitle class="pb-0 primary&#45;&#45;text">-->
<!--                ស្កេនកូត-->
<!--            </v-card-subtitle>-->

            <v-card-text>

                <template v-if="showCamera">
                    <StreamBarcodeReader @decode="onDecode" @loaded="onLoaded">
                    </StreamBarcodeReader>
                    <br>
                </template>

                <v-btn
                    @click="onSubmit"
                    block
                    color="primary"
                    :loading="loading"
                    :disabled="store_id !== '' && items.length > 0  ? disabled : ''">
                    <v-icon>mdi-plus-circle</v-icon>
                    បន្ថែមចូលឃ្លាំង
                </v-btn>

                <br/>


                <v-autocomplete
                    v-model="store_id"
                    :items="store"
                    item-text="city"
                    item-value="id"
                    label="សាខា"
                    dense
                    outlined
                    prepend-inner-icon="mdi-store">
                </v-autocomplete>

                <v-row no-gutters>
                    <v-col
                        cols="6"
                        sm="6"
                    >
                        <v-card
                            class="pa-2"
                            outlined
                            tile
                        >
                            ចំនួនសរុប
                        </v-card>
                    </v-col>

                    <v-col
                        cols="6"
                        sm="6"
                    >
                        <v-card
                            class="pa-2"
                            outlined
                            tile
                        >
                            {{totalItems}} ({{items.length}})
                        </v-card>
                    </v-col>

                </v-row>



            </v-card-text>

        </v-card>

        <div v-for="(item, index) in items" style="margin: 5px">
            <v-card style="padding: 5px">

                <div style="display: flex">

                    <v-list-item-avatar size="60" rounded>
                        <v-img :src="item.avatar"></v-img>
                    </v-list-item-avatar>

                    <div>

                        <p> ឈ្មោះ : {{ item.name }}</p>


                        <div style="display: flex; width: 100%">

                            <v-btn color="red" class="py-5" @click="remove(item)" x-small>
                                <v-icon color="white">mdi-close-circle</v-icon>
                            </v-btn>

                            <div class="pa-1"></div>

                            <v-text-field type="number" outlined dense maxlength="10" :value="item.qty"
                                          style="min-width: 80px; text-align: center"
                                          v-on:keypress="isLetterOrNumber($event)" @change="onChange" @focusin="onFocus(index)"></v-text-field>

                            <div class="pa-1"></div>

                            <v-btn color="primary" class="py-5" @click="minus(item)" x-small>
                                <v-icon color="white">mdi-minus-circle</v-icon>
                            </v-btn>

                            <div class="pa-1"></div>

                            <v-btn color="green" class="py-5" @click="plus(item)" x-small>
                                <v-icon color="white">mdi-plus-circle</v-icon>
                            </v-btn>

                        </div>

                    </div>

                </div>

            </v-card>
        </div>

    </div>

</template>

<script>
    import {StreamBarcodeReader} from "vue-barcode-reader";
    import StoreComponent from "../../components/StoreComponent";
    import ProductComponent from "../../components/ProductComponent";
    import Import from "../../components/import/Import";
    import {convertParams} from "../../utils";
    import axios from "axios";
    import {mapGetters} from "vuex";
    import {CREATE_IMPORT, UPDATE_QTY_WAREHOUSES} from "../../route";

    export default {
        name: "AddStock",
        components: {Import, ProductComponent, StoreComponent, StreamBarcodeReader},
        data() {
            return {
                showCamera: true,
                store_id: '',
                code: '',
                items: [],
                disabled: false,
                loading: false,
                index: 0
            }
        },
        methods: {
            getStoreId(store_id) {
                this.store_id = store_id;
            },
            isLetterOrNumber(e) {
                let char = String.fromCharCode(e.keyCode);
                if (/^[A-Za-z0-9]+$/.test(char)) {
                    return true;
                }
                else {
                    e.preventDefault();
                }

                this.setStorage();
            },
            plus(item) {
                item.qty = item.qty + 1;
                this.setStorage();
            },
            minus(item) {
                if (item.qty > 1) {
                    item.qty = item.qty - 1;
                }
                this.setStorage();
            },
            remove(item) {
                this.items = this.items.filter(i => i.id !== item.id);
                this.setStorage();
            },
            async onDecode(code) {
                let camera_flashing_2 = document.getElementById("camera_flashing_2");
                //console.log(`Decode text from QR code is ${code}`);
                const params = convertParams({code});
                if (this.code !== code) {
                    this.code = code;
                    await axios.get(`/api/auth/search_product?${params}`)
                        .then((result) => {
                            let product = result.data;

                            if (product !== '' && product.id.toString() !== '') {
                                //console.log(result.data);

                                let item = {
                                    id: product.id,
                                    name: product.name,
                                    avatar: product.image,
                                    qty: 1,
                                };

                                if (this.items.length === 0) {
                                    this.items = [item];
                                    //console.log(this.items);
                                    camera_flashing_2.play();
                                    this.setStorage();
                                    return;
                                } else if (this.items.length > 0) {
                                    let index = this.items.findIndex(i => parseInt(i.id) === parseInt(product.id));
                                    if (index < 0) {
                                        this.items = [...this.items, item];
                                        //console.log(this.items);
                                        this.setStorage();
                                        camera_flashing_2.play();
                                        return;
                                    }
                                    this.items[index].qty = parseInt(this.items[index].qty) + 1;
                                    //console.log(this.items);
                                    this.setStorage();
                                    camera_flashing_2.play();
                                }
                            }

                        })
                        .catch(reason => console.log(reason));
                }
            },
            onLoaded() {
                //console.log(`Ready to start scanning barcodes`);
            },
            setStorage() {
                localStorage.setItem('item_import', JSON.stringify(this.items));
            },
            getStorage() {
                let items = JSON.parse(localStorage.getItem('item_import'));
                if (items !== null) {
                    this.items = items;
                }
            },
            onChange(value) {
                //
                //console.log(value);

                if(value == '' || value == null){
                    value = 1;
                }

                this.items[this.index].qty = parseInt(value);

                this.setStorage();

            },
            async onSubmit() {

                this.loading = true;
                this.disabled = true;

                let data = {
                    store_id: this.store_id,
                    data: this.items,
                    type_import: 'transfer',
                    ware_id : 1,
                };

                if(this.store_id === 1 || this.store_id == 1){
                    data.type_import = 'import';
                }

                if(this.store_id === ''){
                    return;
                }

                if(this.items.length < 1){
                    return;
                }

                await axios.post(CREATE_IMPORT, data)
                    .then(response => {
                        if (response.data.message) {
                            this.disabled = false;
                            this.loading = false;
                            this.store_id = '';
                            this.items = [];
                            this.setStorage();

                            window.open(`/receipt?id=${response.data.invoice}&token=aaNLz3PE4FsN8lwABfEzwoGkCEZViWOPcIP9Kdka`,'_blank')
                        }
                    }).catch(() => {
                        this.disabled = false;
                        this.loading = false;
                    });

               // console.log(data);
            },
            onFocus(i) {
                //console.log(i);
                this.index = i;
            },
        },
        mounted() {
            this.getStorage();
        },
        computed: {
            ...mapGetters(['store']),
            totalItems() {
                return this.items.reduce((acc, item) => acc + item.qty, 0);
            }
        },
    }
</script>

<style scoped>
    video {
        width: 100% !important;
    }

    input[type="number"] {
        text-align: center;
    }
</style>
