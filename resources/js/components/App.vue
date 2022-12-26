<template>
    <div>

        <v-app>

            <v-navigation-drawer app absolute v-model="drawer" style="z-index: 100; min-height: 100vh"  class="app-bar">
                <v-list-item class="primary">
                    <v-list-item-content>
                        <v-list-item-title class="text-center white--text font-weight-regular">
                            <a href="/" style="text-decoration: none; color: #fff ">
                                K STOCK MAIN
                            </a>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                        </v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <v-divider></v-divider>

                <v-list dense nav>

                    <v-list-item-group v-model="selectedItem" color="primary">
                        <v-list-item v-for="(item, i) in items" :key="i" link @click="toPage(item.link)">

                            <v-list-item-icon>
                                <v-icon>{{ item.icon }}</v-icon>
                            </v-list-item-icon>

                            <v-list-item-content>
                                <v-list-item-title style="font-size: 14px;">{{ item.title }}</v-list-item-title>
                            </v-list-item-content>

                        </v-list-item>
                    </v-list-item-group>


                    <v-list>
                        <v-list-group v-for="item in itemss" :key="item.title" v-model="item.active"
                                      :prepend-icon="item.action" no-action>
                            <template v-slot:activator>
                                <v-list-item-content>
                                    <v-list-item-title v-text="item.title"></v-list-item-title>
                                </v-list-item-content>
                            </template>

                            <v-list-item v-for="child in item.items" :key="child.title">
                                <v-list-item-content>
                                    <v-list-item-title v-text="child.title"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group>
                    </v-list>

                </v-list>

            </v-navigation-drawer>

            <v-content class="app-bar">
                <v-toolbar
                    color="primary" class="white--text" height="50"
                >
                    <v-icon class="white--text" @click.stop="drawer = !drawer">mdi-apps</v-icon>


                    <v-spacer></v-spacer>
                    <v-btn icon>
                        <v-icon class="white--text">mdi-magnify</v-icon>
                    </v-btn>
                </v-toolbar>


            </v-content>


            <router-view></router-view>
        </v-app>
    </div>

</template>

<script>

    export default {
        data() {
            return {
                drawer: false,
                items: [
                    // {title: 'ផ្ទាំងគ្រប់គ្រង', icon: 'mdi-view-dashboard', link: ''},
                    {title: 'បញ្ជីទំនិញក្នុងឃ្លាំង', icon: 'mdi-cart', link: '/list_warehouse'},
                    {title: 'ទំនិញចូលឃ្លាំង', icon: 'mdi-help-box', link: '/add_stock'},
                    {title: 'ស្លាក​សញ្ញាទំនិញ', icon: 'mdi-help-box', link: '/product_label'},
                    {title: 'បញ្ជីទំនិញចូលតាមឃ្លាំង', icon: 'mdi-help-box', link: '/import_stock'},
                ],
                right: null,
                selectedItem: 1,

                itemss: [

                    // {
                    //     action: 'mdi-silverware-fork-knife',
                    //     //active: true,
                    //     items: [
                    //         { title: 'Breakfast & brunch' },
                    //         { title: 'New American' },
                    //         { title: 'Sushi' },
                    //     ],
                    //     title: 'Dining',
                    // },
                    // {
                    //     action: 'mdi-school',
                    //     items: [{ title: 'List Item' }],
                    //     title: 'Education',
                    // },
                    // {
                    //     action: 'mdi-human-male-female-child',
                    //     items: [{ title: 'List Item' }],
                    //     title: 'Family',
                    // },
                    // {
                    //     action: 'mdi-bottle-tonic-plus',
                    //     items: [{ title: 'List Item' }],
                    //     title: 'Health',
                    // },
                    // {
                    //     action: 'mdi-briefcase',
                    //     items: [{ title: 'List Item' }],
                    //     title: 'Office',
                    // },
                    // {
                    //     action: 'mdi-tag',
                    //     items: [{title: 'List Item'}],
                    //     title: 'Promotions',
                    // },
                ],
            }
        },
        methods: {
            fetch_options: function () {
                this.$store.dispatch('fetchStores');
                this.$store.dispatch('fetchProducts');
            },
            toPage(page) {
                this.$router.push('/ui' + page);
            },

        },
        mounted() {
            this.fetch_options();
        },
    }
</script>

<style>

</style>
