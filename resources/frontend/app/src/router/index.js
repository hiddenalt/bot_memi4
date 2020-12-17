import Home from "../components/views/Home"
import VueRouter from "vue-router"
import i18n from '../plugins/i18n' //i18n.locale

const postfix = i18n.t("app.postfix");

const routes = [
    {
        path: '/',
        component: Home,
        name: "home"
    },
];

const router = new VueRouter({
    mode: 'history',
    base: "/menu/",
    routes
});

router.beforeEach((to, from, next) => {
    // if(typeof to.meta.title == "function"){
    //     document.title = to.meta.title(to);
    // } else {
    //     document.title = "...";
    // }

    let name = to.name;
    if(name != null && name != undefined){
        document.title = i18n.t("screens."+name+".page_title") + postfix;
    }

    next();
});

export default router;
