<template>
    <div>

        <v-card elevation="0">

            <v-card-title>
                <v-icon class="primary--text">mdi-apps</v-icon>
                <h3 class="primary--text font-weight-medium">បញ្ជីផលិតផលឃ្លាំង</h3>
            </v-card-title>

            <template v-if="error">
                <v-alert dense outlined type="error">
                    ការទាញយកទិន្នន័យមានបញ្ហា
                </v-alert>
            </template>

            <v-row>
                <StoreComponent :get-store-id="getStoreId" :fetch-all="get_data"/>
                <ProductComponent :get-product-id="getProductId"/>
                <DateRange :get-date="getDate"/>
                <DateRange :get-date="getEndDate"/>
            </v-row>

            <v-simple-table
                class="elevation-0 t-borders"
                dense
            >
                <template v-slot:default>
                    <thead>
                    <tr>
                        <th class="text-left t-header">
                            វិក្កយបត្រ
                        </th>
                        <th class="text-left t-header" width="120">
                            កាលបរិច្ឆេទនាំចូល
                        </th>

                        <th class="text-left t-header">
                            ឈ្មោះទំនិញ
                        </th>

                        <th class="text-right t-header">
                            ចំនួនចូលឃ្លាំង
                        </th>
                        <th class="text-center t-header">
                            ស្ថានភាព
                        </th>
                        <th class="text-left">
                            ចូលឃ្លាំង
                        </th>
                        <th class="text-left">
                            មកពីឃ្លាំង
                        </th>

                        <th class="text-left">
                            អ្នកប្រើប្រាស់
                        </th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in data" :key="item.id">
                        <td>#{{ item.no }}
                            <a target="_blank" :href="print(item.id)">
                                <v-icon size="18" color="">mdi-receipt</v-icon>
                            </a>
                        </td>
                        <td>{{ item.date }}</td>
                        <td>
                            <v-avatar rounded="50" size="30px">
                                <img :src="item.image" :alt="item.image">
                            </v-avatar>
                            {{ item.name }}
                        </td>
                        <td class="text-right">{{ formatQtyOrCurrencyCall(item.qty) }} pcs</td>
                        <td class="text-center">

                            <template v-if="item.store === item.main_store">
                                <v-chip
                                    class="ma-2"
                                    color="primary"
                                    outlined
                                >
                                    ផ្ទេរទៅឃ្លាំង
                                    <v-icon size="18" right>mdi-checkbox-marked-circle</v-icon>
                                </v-chip>
                            </template>
                            <template v-else>
                                <v-chip
                                    class="ma-2"
                                    color="green"
                                    outlined
                                >
                                    ឃ្លាំងនាំចូល
                                    <v-icon size="18" right> mdi-arrow-right-circle</v-icon>
                                </v-chip>
                            </template>

                        </td>
                        <td> {{ item.store }}</td>
                        <td>{{ item.main_store }}</td>
                        <td>{{ item.username }}</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th width="90px"></th>
                        <th class="text-left">
                            សរុប
                        </th>

                        <th class="text-right" colspan="2">
                            {{ formatQtyOrCurrencyCall(qty) }} pcs
                        </th>

                        <th class="text-right" colspan="4">
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

        <v-fab-transition>
            <v-btn
                v-show="!hidden"
                color="pink"
                dark
                fixed
                bottom
                right
                fab
                elevation="elevation-1"
                @click="dialog = true"
            >
                <v-icon>mdi-plus</v-icon>
            </v-btn>
        </v-fab-transition>

        <!--        modal-->

        <v-row justify="center">
            <v-dialog v-model="dialog" persistent width="600px">
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

                                <!--                                <v-avatar rounded size="150">-->

                                <!--                                    <v-img lazy-src="https://picsum.photos/id/11/10/6" :src="items.image"></v-img>-->

                                <!--                                </v-avatar>-->

                                <!--                                <br/>-->
                                <!--                                <br/>-->

                                <!--                                <audio-->
                                <!--                                    ref="audio"-->
                                <!--                                    preload="auto"-->
                                <!--                                    volume="0.1"-->
                                <!--                                    muted-->
                                <!--                                    loop-->
                                <!--                                >-->
                                <!--                                    <source src="../../public/sounds/camera_flashing.mp3"/>-->
                                <!--                                </audio>-->

                                <div>
                                    <StreamBarcodeReader
                                        @decode="onDecode"
                                        @loaded="onLoaded"
                                    >
                                    </StreamBarcodeReader>
                                </div>

                                <!--                                                                <audio-->
                                <!--                                                                    ref="audio"-->
                                <!--                                                                    src="public/sounds/camera_flashing.mp3">-->
                                <!--                                                                </audio>-->

                                <!--                                <audio controls autoplay>-->
                                <!--                                    <source  src="../../components/sounds/camera_flashing.mp3" type="audio/mp3">-->
                                <!--                                    Your browser does not support the audio element.-->
                                <!--                                </audio>-->

                                <!--                                <qrcode-stream @decode="onDecode"  :track="this.paintBoundingBox" @init="logErrors"></qrcode-stream>-->


                                <!--                                <qrcode-stream></qrcode-stream>-->
                                <!--                                <qrcode-drop-zone></qrcode-drop-zone>-->
                                <!--                                <qrcode-capture></qrcode-capture>-->

                                <!--                                <v-text-field dense v-model="items.id" outlined label="" required value="0"-->
                                <!--                                              style="display:none"></v-text-field>-->

                                <!--                                <v-text-field dense v-model="items.proudct_name" type="text" outlined label="ឈ្មោះទំនិញ"-->
                                <!--                                              required :rules="nameRules" v-on:change="onChange"></v-text-field>-->

                                <!--                                <v-text-field dense v-model="items.price" type="number" outlined label="តំលៃ​លក់"-->
                                <!--                                              required-->
                                <!--                                              :rules="priceRules" v-on:change="onChange"></v-text-field>-->

                                <!--                                <v-text-field dense v-model="items.quantity" type="number" outlined-->
                                <!--                                              label="ចំនួនក្នុងឃ្លាំង"-->
                                <!--                                              required :rules="qtyRules" v-on:change="onChange"></v-text-field>-->

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


        <!--        modal-->

    </div>
</template>

<script>
    import StoreComponent from "../../components/StoreComponent";
    import ProductComponent from "../../components/ProductComponent";
    import DateRange from "../../components/DateRange";
    import axios from 'axios';
    import {convertParams, formatQtyOrCurrency, pageSize} from "../../utils";
    import {GET_PRODUCTS_IMPORT} from "../../route";
    import {StreamBarcodeReader} from "vue-barcode-reader";
    // import sound from '../../components/sounds/camera_flashing.mp3';

    export default {
        name: "ImportStock",
        components: {
            DateRange, ProductComponent, StoreComponent,
            StreamBarcodeReader
        },
        data: () => ({
            pageSize: pageSize,
            isLoading: false,
            data: [],
            searchable: {
                page: 1,
                page_size: 20,
                store_id: '',
                product_id: '',
                state_date: '',
                end_date: '',
            },
            error: false,
            total: 0,
            dialog: false,
            disabled: false,
            loading: false,
            valid: true,
            items: {
                //qty: 0
            },
            nameRules: [
                v => !!v || 'សូមបញ្ចូលឈ្មោះផលឺតផល',
            ],
            priceRules: [
                v => !!v || 'សូមបញ្ចូលតម្លៃ',
            ],
            qtyRules: [
                v => !!v || 'សូមបញ្ចូលចំនួន',
            ],
        }),
        methods: {
            getStoreId(store_id) {
                this.searchable.store_id = store_id;
                this.get_data();
            },
            getProductId(product_id) {
                this.searchable.product_id = product_id;
                this.get_data();
            },
            getDate(state_date) {
                this.searchable.state_date = state_date;
                this.get_data();
            },
            getEndDate(end_date) {
                this.searchable.end_date = end_date;
                this.get_data();
            },
            async get_data() {
                //this.audio.play();
                // let audio = this.$refs.audio;
                // audio.play();


                //const audio = new Audio(sound);
                //await audio.play();

                //this.$refs.audio.play()


                this.isLoading = true;
                const params = convertParams(this.searchable);

                await axios
                    .get(`${GET_PRODUCTS_IMPORT}?${params}`)
                    .then(response => {
                        //console.log(response.data);
                        this.data = response.data.data;
                        this.total = Math.ceil(response.data.total / this.searchable.page_size);
                        this.isLoading = false;
                        let qty = 0;
                        response.data.data.forEach((item) => {
                            qty = qty + parseFloat(item.qty);
                        });
                        this.qty = qty;
                        this.error = false;
                    }).catch(() => {
                        this.error = true;
                    });
            },
            formatQtyOrCurrencyCall: (value) => formatQtyOrCurrency(value),
            async submit() {

            },

            logErrors(promise) {
                promise.catch(console.error)
            },
            paintBoundingBox(detectedCodes, ctx) {
                for (const detectedCode of detectedCodes) {
                    const {boundingBox: {x, y, width, height}} = detectedCode

                    ctx.lineWidth = 2
                    ctx.strokeStyle = '#007bff'
                    ctx.strokeRect(x, y, width, height)
                }
            },
            onDecode(text) {
                console.log(`Decode text from QR code is ${text}`);
                // let audio = this.$refs.audio;
                //audio.play();
            },
            onLoaded() {
                console.log(`Ready to start scanning barcodes`)
            },
            print(id){
                return `/receipt?id=${id}&token=aaNLz3PE4FsN8lwABfEzwoGkCEZViWOPcIP9Kdka`;
            }
        },
        mounted() {
            this.get_data();
        },
    }
</script>
