import { createWebHistory, createRouter } from "vue-router";
import Home from "./components/Home.vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";


const routes = [
  {
    path: "/",
    name: "home",
    component: Home,
  },
  {
    path: "/home",
    component: Home,
  },
  {
    path: "/login",
    component: Login,
  },
  {
    path: "/register",
    component: Register,
  },
  {
    path: "/profile",
    name: "profile",
    component: () => import("./components/Profile.vue"),
  },
  {
    path: "/seller",
    name: "seller",
    component: () => import("./components/SellerBoard.vue"),
  },
  {
    path: "/buyer",
    name: "buyer",
    component: () => import("./components/BuyerBoard.vue"),
  },
  {
    path: '/products/create',
    name: 'product.create',
    component: () => import('./components/products/ProductCreate.vue')
  },
  {
    path: '/products/edit/:id',
    name: 'product.edit',
    component: () => import('./components/products/ProductEdit.vue')
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// router.beforeEach((to, from, next) => {
//   const publicPages = ['/login', '/register', '/home'];
//   const authRequired = !publicPages.includes(to.path);
//   const loggedIn = localStorage.getItem('user');

//   // trying to access a restricted page + not logged in
//   // redirect to login page
//   if (authRequired && !loggedIn) {
//     next('/login');
//   } else {
//     next();
//   }
// });

export default router;