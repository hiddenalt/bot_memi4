<template>
    <v-app class="app">

        <v-navigation-drawer
            v-model="drawer"
            temporary
            fixed
        >
            <v-list>
                <v-list-item
                    :key="item.label"
                    @click="$router.push(item.path)"
                    link
                    v-for="item in menu"
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ $t(item.label) }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <template v-slot:append>
                <div class="pa-2">
                    <v-menu transition="scroll-y-transition">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                block
                                class="mb-2"
                                color="secondary"
                                v-bind="attrs"
                                v-on="on"
                            >
                                {{$t('language')}}
                            </v-btn>
                        </template>
                        <v-list>
                            <v-list-item
                                :key="lang"
                                :value="lang"
                                @click="changeLocale(lang)"
                                link
                                v-for="lang in langs"
                            >
                                <v-list-item-title v-text="$t('language', lang)"></v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>


                    <v-tooltip top v-if="!$vuetify.theme.dark">
                        <template v-slot:activator="{ on }">
                            <v-btn @click="darkMode" block color="info" v-on="on">
                                <v-icon class="mr-1" color="yellow">mdi-white-balance-sunny</v-icon>
                                {{$t("app.dark_mode_is_off")}}
                            </v-btn>
                        </template>
                        <span>{{$t("app.dark_mode_on")}}</span>
                    </v-tooltip>

                    <v-tooltip top v-else>
                        <template v-slot:activator="{ on }">
                            <v-btn @click="darkMode" block color="info" v-on="on">
                                <v-icon class="mr-2">mdi-moon-waxing-crescent</v-icon>
                                {{$t("app.dark_mode_is_on")}}
                            </v-btn>
                        </template>
                        <span>{{$t("app.dark_mode_off")}}</span>
                    </v-tooltip>

<!--                    <v-btn block>-->
<!--                        Logout-->
<!--                    </v-btn>-->
                </div>
            </template>
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
            >{{$t("screens."+this.$router.currentRoute.name+".page_title")}}</v-toolbar-title>

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



<!--        <v-tooltip bottom>-->
<!--            <template v-slot:activator="{ on }">-->
<!--                <v-btn-->
<!--                    href=""-->
<!--                    target="_blank"-->
<!--                    icon-->
<!--                    v-on="on"-->
<!--                >-->
<!--                    <v-icon>mdi-vk</v-icon>-->
<!--                </v-btn>-->
<!--            </template>-->
<!--            <span>VK</span>-->
<!--        </v-tooltip>-->

<!--        <v-tooltip bottom>-->
<!--            <template v-slot:activator="{ on }">-->
<!--                <v-btn-->
<!--                    href=""-->
<!--                    target="_blank"-->
<!--                    icon-->
<!--                    v-on="on"-->
<!--                >-->
<!--                    <v-icon>mdi-telegram</v-icon>-->
<!--                </v-btn>-->
<!--            </template>-->
<!--            <span>Telegram</span>-->
<!--        </v-tooltip>-->

    </v-app-bar>

    <v-content>
<!--      <ApolloExample/>-->
        <router-view />
    </v-content>
  </v-app>
</template>

<style lang="scss" scoped>
    /*div.app {*/
    /*    background: rgba(0,0,0,0) !important;*/
    /*}*/
    /*div.app:before {*/
    /*    opacity: 0.4 !important;*/
    /*    background: #fff url('~@/assets/logo.png') no-repeat fixed left calc(100vh - 200px) !important;*/
        /* width: 100%;
        height: 100%; */
        /*top: 0;*/
        /*left: 0;*/
        /*position: fixed;*/
        /*z-index: -1;*/
        /*content: "";*/
    /*}*/
</style>

<script>
    import ApolloExample from './components/ApolloExample';

    export default {
    name: 'App',

    components: {
        ApolloExample,
    },

    data: () => ({
        drawer: false,
        langs: ['ru', 'en'], // TODO: sync with available in i18n plugin
        menu: [
            {
                label: "app.menu_options.home",
                path: "/",
                icon: "mdi-home"
            },
            {
                label: "app.menu_options.meme_builder",
                path: "/create-meme/",
                icon: "mdi-creation"
            },
            {
                label: "app.menu_options.data_packs",
                path: "/vocabulary/",
                icon: "mdi-book-multiple"
            },
            {
                label: "app.menu_options.conversations",
                path: "/conversations/",
                icon: "mdi-message"
            },
            {
                label: "app.menu_options.mood",
                path: "/mood/",
                icon: "mdi-emoticon-wink"
            },
            {
                label: "app.menu_options.usage",
                path: "/create-meme/",
                icon: "mdi-account-question"
            },
            {
                label: "app.menu_options.api",
                path: "/api/",
                icon: "mdi-api"
            },
        ]
    }),

    mounted: function(){
        this.$nextTick(function () {
            let darkMode = localStorage.getItem('dark_mode');
            if(darkMode === "true")
                this.$vuetify.theme.dark = true;

            let locale = localStorage.getItem('locale');
            if(locale != null && locale != undefined && this.$te('language', locale))
                this.$i18n.locale = locale;
        });
    },

    methods:{
        darkMode: function(){
            let value = !this.$vuetify.theme.dark;
            this.$vuetify.theme.dark = value;
            localStorage.setItem('dark_mode', ""+value);
        },
        changeLocale: function(lang){
            this.$i18n.locale = lang;
            localStorage.setItem('locale', lang);
        }
    }
};
</script>
