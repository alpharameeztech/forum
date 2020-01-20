import VueRouter from 'vue-router';

import Channels from './forum/Channels'

import Threads from './forum/Threads'

import Replies from './forum/Replies'

import Publishers from "./forum/Publishers";

import Members from "./forum/Members";

import Branding from './forum/Branding'

import Dashboard from './forum/Dashboard/Index'

import ThemeSettings from "./forum/ThemeSettings";

import Settings from "./forum/Settings";

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin',
            component: Dashboard,
            meta: {

            }
        },
        {
            path: '/admin/dashboard',
            component: Dashboard,
            meta: {

            }
        },
        {
            path: '/admin/channels',
            component: Channels,
            meta: {

            }
        },
        {
            path: '/admin/threads',
            component: Threads,
            meta: {

            }
        },
        {
            path: '/admin/replies',
            component: Replies,
            meta: {

            }
        },
        {
            path: '/admin/publishers',
            component: Publishers,
            meta: {

            }
        },
        {
            path: '/admin/members',
            component: Members,
            meta: {

            }
        },
        {
            path: '/admin/themes',
            component: ThemeSettings,
            meta: {

            }
        },
        {
            path: '/admin/settings',
            component: Settings,
            meta: {

            }
        }
    ],
});
export default router;
