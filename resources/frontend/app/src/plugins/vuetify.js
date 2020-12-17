import Vue from 'vue';
import Vuetify from 'vuetify/lib';
import ru from 'vuetify/es5/locale/ru';
import en from 'vuetify/es5/locale/en';
// import i18n from '../i18n' //i18n.locale

Vue.use(Vuetify);

// TODO: match locale with i18n plugin

export default new Vuetify({
    theme: {
        options: {
            customProperties: true,
        },
        themes: {
            light: {
                primary: "#0D90FF",
                secondary: "#3f51b5",
                accent: "#0BE656",

                error: "#f44336",
                warning: "#ffbd00",
                info: "#00bcd4",
                success: "#4caf50",

                background: "#edeef0"
            },
            dark: {
                primary: "#0d479a",
                secondary: "#404f9b",
                accent: "#0b9b3f",

                error: "#9b2c26",
                warning: "#9b6e00",
                info: "#008e9b",
                success: "#4e9b4e",

                // background: colors.indigo.base
            }
        },
    },
    lang: {
        locales: { ru, en },
        current: "en",
    }
});
