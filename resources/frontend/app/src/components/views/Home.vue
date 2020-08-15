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

            <h1 class="display-2 mb-2">Менюшечка</h1>
            <p class="text-center">
                Разбита по категориям.<br>
                Где надо залогиниться - покажет,<br>
                где не надо - юзейте без авторизации.
            </p>

            <v-col class="text-center d-flex flex-row justify-center flex-wrap" cols="1">
                <div class="my-2" v-for="item in menu">
                    <v-btn large :color="item.color" @click="$router.push(item.path)" width="350px">
                        <v-icon left>{{item.icon}}</v-icon>
                        {{item.label}}
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
                    label: "Конструктор мемов",
                    color: "info",
                    path: "/create-meme/",
                    icon: "mdi-creation"
                },
                {
                    label: "Мои словари",
                    color: "success",
                    path: "/vocabulary/",
                    icon: "mdi-book-multiple"
                },
                {
                    label: "Мои беседы",
                    color: "success",
                    path: "/conversations/",
                    icon: "mdi-message"
                },
                {
                    label: "Настроение бота",
                    color: "primary",
                    path: "/create-meme/",
                    icon: "mdi-emoticon-wink"
                },
                {
                    label: "Как использовать бота",
                    color: "primary",
                    path: "/create-meme/",
                    icon: "mdi-account-question"
                },
                {
                    label: "API",
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
