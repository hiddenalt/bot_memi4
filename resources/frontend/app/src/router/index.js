import Home from "../components/views/Home";
import VueRouter from "vue-router";

const postfix = " | Менюшка";

const routes = [
    {
        path: '/',
        component: Home,
        meta: {
            title: route => {
                return "Главная" + postfix;
            }
        }
    },
];

const router = new VueRouter({
    mode: 'history',
    base: "/menu/",
    routes
});

router.beforeEach((to, from, next) => {
    if(typeof to.meta.title == "function"){
        document.title = to.meta.title(to);
    } else {
        document.title = "...";
    }

    next();
});

export default router;
