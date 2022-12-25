<template>
    <v-col cols="12" sm="4" md="3">
        <v-autocomplete
            prepend-icon="mdi-store"
            v-model="store_id"
            :items="store"
            clearable
            item-text="name"
            item-value="id"
            label="សាខា"
            @change="onChange"
            dense
        >
        </v-autocomplete>
    </v-col>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "StoreComponent",
        props: ['getStoreId',''],
        data: () => ({
            store_id: '',
        }),
        methods: {
            onChange() {
                this.getStoreId(this.store_id);
            }
        },
        computed: {
            ...mapGetters(['store'])
        },
    }
</script>
