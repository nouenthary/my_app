<template>
    <v-col cols="12" sm="4" md="3">
        <v-menu
            v-model="start"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="auto"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    v-model="state_date"
                    label="កាលបរិច្ឆេទ"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    clearable
                    dense
                ></v-text-field>
            </template>
            <v-date-picker
                v-model="state_date"
                @change="onChange()"
            ></v-date-picker>
        </v-menu>
    </v-col>
</template>
<script>
    let date = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);
    export default {
        name: "DateRange",
        props: ['getDate'],
        data: () => ({
            start: false,
            state_date: '',
        }),
        methods: {
            onChange() {
                this.state = false;
                this.getDate(this.state_date);
            }
        }
    }
</script>
