<template>
    <v-app>

        <!-- TODO: translate the frontend to English -->

        <v-navigation-drawer
            v-model="drawer"
            absolute
            temporary
        >
            <v-list-item>
                <v-list-item-avatar>
                    <v-img src="https://randomuser.me/api/portraits/men/78.jpg"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <!-- TODO -->
                    <v-list-item-title>TODO</v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list dense>

                <!-- Главная -->
                <!-- Глобалки -->
                <!-- Fake message (прием) -->
                <!-- Fake message (отправка сообщения) -->
                <!-- GraphQL playground -->

                <v-list-item
                    link
                >
                    <v-list-item-icon>
                        <v-icon>mdi-vk</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>123</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            app
            color="primary"
            dark
        >
            <div class="d-flex align-center">
                <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

                <v-img
                    alt="Vuetify Logo"
                    class="shrink mr-2 ml-2"
                    contain
                    :src="require('./assets/logo.svg')"
                    transition="scale-transition"
                    width="40"
                />

                <v-toolbar-title
                    class="hidden-sm-and-down"
                    transition="scale-transition"
                >Админка</v-toolbar-title>

                <!--
                <v-img
                  alt="Vuetify Name"
                  class="shrink mt-1 hidden-sm-and-down"
                  contain
                  min-width="100"
                  src="https://cdn.vuetifyjs.com/images/logos/vuetify-name-dark.png"
                  width="100"
                />
                -->
            </div>

            <v-spacer></v-spacer>



            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        href="https://vk.com/bot_memi4"
                        target="_blank"
                        icon
                        v-on="on"
                    >
                        <v-icon>mdi-vk</v-icon>
                    </v-btn>
                </template>
                <span>Бот в ВКонтакте</span>
            </v-tooltip>

            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        href="tg.example.com/bot_memi4"
                        target="_blank"
                        icon
                        v-on="on"
                    >
                        <v-icon>mdi-telegram</v-icon>
                    </v-btn>
                </template>
                <span>Бот в Telegram</span>
            </v-tooltip>

        </v-app-bar>

        <v-content>
            <!--      <ApolloExample/>-->
            <router-view />
        </v-content>
    </v-app>
</template>

<script>
    import ApolloExample from './components/ApolloExample';

    export default {
        name: 'App',

        components: {
            ApolloExample,
        },

        data: () => ({
            drawer: false,
            menu: [
                {
                    label: ""
                }
            ]
        }),
    };
</script>
