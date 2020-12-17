<template>
    <v-container
        class="fill-height"
        fluid
    >
        <v-row
            class="d-flex flex-column"
            align="center"
            justify="center"
        >

            <h1 class="display-2 mb-2">{{$t("screens.home.title")}}</h1>
            <p class="text-center">
                {{$t("screens.home.hint")}}
            </p>

            <v-col class="text-center d-flex flex-row justify-center flex-wrap" cols="1">
                <div class="my-2" v-for="item in menu">
                    <v-btn large :color="item.color" @click="$router.push(item.path)" width="350px">
                        <v-icon left>{{item.icon}}</v-icon>
                        {{$t(item.label)}}
                    </v-btn>
                </div>
            </v-col>

        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "Home",
        mounted (){

        },
        methods: {

        },
        data: () => ({
            menu: [
                {
                    label: "screens.home.menu_options.meme_builder",
                    color: "info",
                    path: "/create-meme/",
                    icon: "mdi-creation"
                },
                {
                    label: "screens.home.menu_options.data_packs",
                    color: "success",
                    path: "/vocabulary/",
                    icon: "mdi-book-multiple"
                },
                {
                    label: "screens.home.menu_options.conversations",
                    color: "success",
                    path: "/conversations/",
                    icon: "mdi-message"
                },
                {
                    label: "screens.home.menu_options.mood",
                    color: "primary",
                    path: "/mood/",
                    icon: "mdi-emoticon-wink"
                },
                {
                    label: "screens.home.menu_options.usage",
                    color: "primary",
                    path: "/create-meme/",
                    icon: "mdi-account-question"
                },
                {
                    label: "screens.home.menu_options.api",
                    color: "error",
                    path: "/api/",
                    icon: "mdi-api"
                },
            ]
        })
    }
</script>

<style scoped>

</style>
