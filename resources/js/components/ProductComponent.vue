<template>
    <v-col cols="12" sm="4" md="3">
        <v-autocomplete
            prepend-icon="mdi-weight"
            v-model="product_id"
            :items="products"
            color="blue-grey lighten-2"
            label="ឈ្មោះទំនិញ"
            item-text="name"
            item-value="id"
            clearable
            @change="onChange"
            dense
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
    </v-col>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "ProductComponent",
        props: ['getProductId'],
        data: () => ({
            isUpdating: false,
            product_id: ''
        }),
        computed: {
            ...mapGetters(['products']),
        },
        methods: {
            onChange() {
                this.getProductId(this.product_id);
            }
        },
    }
</script>
