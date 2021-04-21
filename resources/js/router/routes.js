export default [
    {
        path: "/",
        component: require("../views/layout/FrontLayout.vue").default,
        children: [
            {
                path: "",
                name: "home",
                component: require("../views/Home.vue").default
            },
            {
                path: "post/:slug",
                name: "postShow",
                component: require("../views/post/PostShow.vue").default
            },
            {
                path: "posts/draft",
                name: "myposts",
                component: require("../views/post/MyPosts.vue").default,
                meta: {
                    auth: true
                }
            },
            {
                path: "posts/create",
                name: "create-post",
                component: require("../views/post/Create.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "posts/category/:slug",
                name: "post-category",
                component: require("../views/post/PostCategory.vue").default
            },
            {
                path: "posts/:slug/edit",
                name: "edit-post",
                component: require("../views/post/Edit.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "drafts/:link/save",
                name: "save-post",
                component: require("../views/post/SavePost.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "posts/:slug/update",
                name: "update-post",
                component: require("../views/post/UpdatePost.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "drafts/:link",
                name: "update-draft",
                component: require("../views/post/Create.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "profile",
                name: "profile",
                component: require("../views/user/Profile.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "liked-posts",
                name: "liked-posts",
                component: require("../views/user/LikedPost.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "bookmarked-posts",
                name: "bookmarked-posts",
                component: require("../views/user/BookmarkedPost.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            },
            {
                path: "@:username",
                name: "UserPosts",
                component: require("../views/user/UserPosts.vue").default,
                meta: {
                    auth: true,
                    verified: true
                }
            }
        ]
    },
    {
        path: "/verify",
        name: "verify-email",
        component: require("../views/auth/verify.vue").default,
        meta: {
            auth: true
        }
    },
    {
        path: "/login",
        name: "login",
        component: require("../views/auth/Login.vue").default,
        meta: {
            guest: true
        }
    },
    {
        path: "/register",
        name: "register",
        component: require("../views/auth/Register.vue").default,
        meta: {
            guest: true
        }
    },
    {
        path: "/reset/password",
        name: "reset-email-password",
        component: require("../views/auth/ResetEmailPassword.vue").default,
        meta: {
            guest: true
        }
    },
    {
        path: "/reset/password/:token",
        name: "reset-password",
        component: require("../views/auth/ResetPassword.vue").default,
        meta: {
            guest: true
        }
    },
    {
        path: "/admin",
        component: require("../views/layout/AdminLayout.vue").default,
        children: [
            {
                path: "dashboard",
                component: require("../views/admin/Dashboard.vue").default,
                name: "admin_dashboard"
            }
        ]
    },
    {
        path: "/404",
        name: "notfound",
        component: require("../views/error/NotFound.vue").default
    },
    {
        path: "/403",
        name: "accessdenied",
        component: require("../views/error/AccessDenied.vue").default
    },
    {
        path: "*",
        name: "NotFoundMain",
        component: require("../views/error/NotFound.vue").default
    }
];
