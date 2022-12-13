<template>
    <div class="containers">

        <v-app>
            <!-- <v-navigation-drawer app>
                
                I'm an example component.
            </v-navigation-drawer> -->

            <v-app-bar app color="indigo accent-2" dense dark>
                <!-- -->
                <v-app-bar-nav-icon></v-app-bar-nav-icon>

                <v-toolbar-title>Home Page </v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn icon>
                    <v-icon>mdi-heart</v-icon>
                </v-btn>

                <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>

                <v-menu left bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn icon v-bind="attrs" v-on="on">
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item v-for="n in 5" :key="n" @click="() => { }">
                            <v-list-item-title>Option {{ n }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>

            </v-app-bar>

            <!-- Sizes your content based upon application components -->
            <v-main>

                <!-- Provides the application the proper gutter -->
                <v-container fluid>
                    <router-view></router-view>
                </v-container>
            </v-main>

            <v-footer app>
                <!-- -->
            </v-footer>
        </v-app>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';

console.log(thary);

export default {

    methods: {   
        fetch_ottions: function(){
            this.$store.dispatch('fetchStores');  
            this.$store.dispatch('fetchProducts');  
        }   
    },
    mounted() {                 
        this.fetch_ottions();    
    },   
}
</script>

<style>
   
</style>
