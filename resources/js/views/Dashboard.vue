<template>
    <div>

        <div style="display: flex">


            <v-card
                width="120"
                style="text-align: center"
                @click="toPage('/ui/import')"
            >
                <v-card-text>
                    <v-icon size="50" color="primary">mdi-warehouse</v-icon>
                    <p>ឃ្លាំងទំនិញ</p>
                </v-card-text>
            </v-card>


            <v-card
                width="120"
                style="text-align: center"
                @click="toPage('/ui/transfer')"
            >
                <v-card-text>
                    <v-icon size="50" color="primary">mdi-home</v-icon>
                    <p>ផ្ទេរទំនិញ</p>
                </v-card-text>
            </v-card>

            <v-card
                width="120"
                style="text-align: center"
            >
                <v-card-text>
                    <v-icon size="50" color="primary">mdi-shopping</v-icon>
                    <p>បញ្ជា​រ​ទិញ</p>
                </v-card-text>
            </v-card>

            <v-card
                width="120"
                style="text-align: center"
            >
                <v-card-text>
                    <v-icon size="50" color="primary">mdi-book</v-icon>
                    <p>របាយការណ៏</p>
                </v-card-text>
            </v-card>

        </div>

    </div>
</template>

<script>
    export default {
        name: "Dashboard",
        data: () => ({}),
        methods:{
            toPage(path = '/ui'){
                this.$router.push({ path})
            }
        }
    }
</script>

<style scoped>
    .v-card {
        margin: 10px;
        text-decoration: none;
    }
</style>
