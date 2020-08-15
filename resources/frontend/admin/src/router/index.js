import Home from "../components/views/Home";
import Login from "../components/views/Login";
import VueRouter from "vue-router";

// TODO: translate the frontend to English

const postfix = " | Админка";

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
    {
        path: '/login/',
        component: Login,
        meta: {
            title: route => {
                return "Вход" + postfix;
            }
        }
    },
];

const router = new VueRouter({
    mode: 'history',
    base: "/admin/",
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
