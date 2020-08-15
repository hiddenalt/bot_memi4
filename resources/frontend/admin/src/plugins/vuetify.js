import Vue from 'vue';
import Vuetify from 'vuetify/lib';
import ru from 'vuetify/es5/locale/ru';
import en from 'vuetify/es5/locale/en';

Vue.use(Vuetify);

export default new Vuetify({
    theme: {
        options: {
            customProperties: true,
        },
        themes: {
            light: {
                // primary: "#673ab7",
                primary: "#0080ff",
                secondary: "#3f51b5",
                accent: "#2196f3",

                error: "#f44336",
                warning: "#ffbd00",
                info: "#00bcd4",
                success: "#4caf50",

                background: "#edeef0"
            },
        },
    },
    lang: {
        locales: { ru, en },
        current: 'ru',
    }
});
