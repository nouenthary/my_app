<template>
    <v-card  class="elevation-0">
        <v-card-title>
            List Warehouse Product
        </v-card-title>

        <!-- <v-btn @click="$store.commit('INCREASE')">Increase</v-btn> -->

        <v-row>
            <v-col cols="12" sm="6" md="4">

                <v-autocomplete default="" v-model="store_id" :items="store" clearable :loading="isLoading"
                    item-text="name" item-value="id" label="សាខា" @change="get_data()"></v-autocomplete>
                <!-- outlined -->
            </v-col>

            <v-col cols="12" sm="6" md="4">

                <!-- <v-text-field v-model="title" counter="25" hint="This field uses counter prop"
                    label="Regular"></v-text-field> -->

                <v-autocomplete v-model="product_id" :disabled="isUpdating" :items="products" default=""
                    color="blue-grey lighten-2" label="ឈ្មោះទំនិញ" item-text="name" item-value="id" clearable
                    @change="get_data()">
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
                                <img :src="`${data.item.image}`">
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
        <v-simple-table  class="elevation-1">
            <template v-slot:default>
                <thead>
                    <tr>
                        <th width="80px">រូបភាព</th>
                        <th class="text-left">
                            ឈ្មោះទំនិញ                            
                        </th>
                        <th class="text-left">
                            ចំនួន
                        </th>
                        <th class="text-left">
                            សាខា
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in data" :key="item.id">

                        <td>
                            <v-avatar size="36px">
                                <img :src="item.image" :alt="item.image">
                            </v-avatar>
                        </td>
                        <td>{{ item.proudct_name }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.store_name }}</td>
                    </tr>
                </tbody>
                <!-- {{
                        store_id
                }}
                {{ product_id }}
                {{ page }} -->
            </template>
        </v-simple-table>
        <div class="text-center">
            <v-pagination v-model="page" :length="total" class="my-4" :total-visible="10" prev-icon="mdi-menu-left"
                next-icon="mdi-menu-right" @input="get_data()"></v-pagination>
        </div>

    </v-card>
</template>

<script>
// let token = document.querySelector('meta[name="csrf-token"]').content;
// console.log(token);
import { mapState, mapGetters } from 'vuex';
export default {
    name: 'Import',
    data() {
        return {
            page: 1,
            title: ' ',
            store_id: '',
            product_id: '',
            isLoading: false,
            model: null,
            search: null,
            isUpdating: false,
            friends: [],
            data: [],
            total: 1
        }
    },
    methods: {
        get_data: async function () {
            if (this.store_id == null) {
                this.store_id = '';
            }
            if (this.product_id == null) {
                this.product_id = '';
            }
            if (this.page == null) {
                this.page = 1;
            }
            await axios
                .get(`/api/get_warehouses_stock?store_id=${this.store_id}&product_id=${this.product_id}&page=${this.page}`)
                .then(response => {
                    this.data = response.data.data;
                    this.total = response.data.total;
                });

        },
    },
    mounted() {
        this.get_data();
    },
    computed: {
        ...mapState({
            count: state => state.count
        }),
        ...mapGetters(['store', 'products']),
    },
    watch: {
    }
}
</script>

<style>
.v-card {
    padding: 10px;
}
</style>