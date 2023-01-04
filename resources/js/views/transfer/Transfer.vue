<template>
    <div>
        <v-dialog
            v-model="dialog"
            hide-overlay
            transition="dialog-bottom-transition"
            width="600px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-icon>mdi-plus-circle</v-icon>
                    បន្ថែុមថ្មី
                </v-btn>
            </template>

            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-btn
                        icon
                        dark
                        @click="dialog = false"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        <v-icon>mdi-warehouse</v-icon>
                        ផ្ទេរទំនិញ
                    </v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            dark
                            text
                            @click="dialog = false"
                        >
                            Save
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-container fluid>
                    <v-row>
                        <v-col
                            cols="12"
                            sm="6"
                            md="6"
                        >
                            <v-text-field
                                label="វិក្កយបត្រ"
                                required
                                outlined
                                dense
                                prepend-inner-icon="mdi-plus-circle"
                            ></v-text-field>
                        </v-col>


                        <v-col cols="12" sm="6" md="6">
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
                                        prepend-inner-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        clearable
                                        dense
                                        outlined
                                    ></v-text-field>
                                </template>

                                <v-date-picker
                                    v-model="state_date"
                                    @change="onChange()"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="6"
                        >
                            <v-text-field
                                label="ឃ្លាំង"
                                required
                                outlined
                                dense
                                prepend-inner-icon="mdi-plus-circle"
                            ></v-text-field>
                        </v-col>

                        <v-col
                            cols="12"
                            sm="6"
                            md="6"
                        >
                            <v-text-field
                                label="ផ្ទេរទៅឃ្លាំង"
                                required
                                outlined
                                dense
                                prepend-inner-icon="mdi-plus-circle"
                            ></v-text-field>
                        </v-col>

                    </v-row>


                </v-container>


                <v-divider></v-divider>
                <v-list
                    three-line
                    subheader
                >
                    <v-subheader>General</v-subheader>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="notifications"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Notifications</v-list-item-title>
                            <v-list-item-subtitle>Notify me about updates to apps or games that I downloaded
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="sound"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Sound</v-list-item-title>
                            <v-list-item-subtitle>Auto-update apps at any time. Data charges may apply
                            </v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item>
                        <v-list-item-action>
                            <v-checkbox v-model="widgets"></v-checkbox>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Auto-add widgets</v-list-item-title>
                            <v-list-item-subtitle>Automatically add home screen widgets</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "Transfer",
        data: () => ({
            dialog: true,
            notifications: false,
            sound: true,
            widgets: false,
            start: false,
            state_date: '',
        }),
    }
</script>

<style scoped>

</style>
