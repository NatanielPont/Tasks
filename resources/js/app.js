import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import './bootstrap'
import AppComponent from './components/App.vue'
import ExampleComponent from './components/ExampleComponent.vue'
import Tasks from './components/Tasks.vue'
import Tags from './components/Tags.vue'
import TasksTailwind from './components/TasksTailwind.vue'
import Tasques from './components/Tasques.vue'
import LoginForm from './components/LoginForm.vue'
// import ResetPasswordForm from './components/ResetPasswordForm'
// import EditableForm from './components/EditableForm'
import RegisterForm from './components/RegisterForm.vue'
import UserList from './components/UserList'
import UserSelect from './components/UserSelect'
// import Snackbar from './components/ui/SnackBar'
import snackbar from './plugins/snackbar'
import confirm from './plugins/confirm'

import permissions from './plugins/permissions'
import Impersonate from './components/Impersonate'
import GitInfo from './components/git/GitInfoComponent'
import Tema from './components/Tema.vue'
import Profile from './components/Profile'
import ServiceWorker from './components/ServiceWorker'
import Changelog from './components/changelog/ChangelogComponent.vue'
import Notifications from './components/notifications/Notifications'
import NotificationsWidget from './components/notifications/NotificationsWidget'
import UserSelectComponent from './components/users/UsersSelectComponent'
import Navigation from './components/Navigation'
import TreeView from 'vue-json-tree-view'
import DataIteratorTasks from './components/DataIteratorTasks'
import DataTableTasks from './components/DataTableTasks'
// import VueTimeago from 'vue-timeago'

const PRIMARY_COLOR_KEY = 'primary_color_key'

const primaryColor = window.localStorage.getItem(PRIMARY_COLOR_KEY) || '#2BB0ED' // '#2680C2'
window.Vue = Vue
window.Vuetify = Vuetify
// window.Vue.use(VueTimeago, {
//   locale: 'ca', // Default locale
//   locales: {
//     'ca': require('date-fns/locale/ca')
//   }
// })

window.Vue.use(TreeView)

window.Vue.use(window.Vuetify, {
  theme: {
    primary: {
      base: primaryColor,
      lighten1: '#40C3F7',
      lighten2: '#5ED0FA',
      lighten3: '#81DEFD',
      lighten4: '#B3ECFF',
      lighten5: '#E3F8FF',
      darken1: '#1992D4',
      darken2: '#127FBF',
      darken3: '#0B69A3',
      darken4: '#035388'
    },
    secondary: {
      base: '#616E7C',
      lighten1: '#7B8794',
      lighten2: '#9AA5B1',
      lighten3: '#CBD2D9',
      lighten4: '#E4E7EB',
      lighten5: '#F5F7FA',
      darken1: '#52606D',
      darken2: '#3E4C59',
      darken3: '#323F4B',
      darken4: '#1F2933'
    },
    accent: {
      base: '#DA127D',
      lighten1: '#E8368F',
      lighten2: '#F364A2',
      lighten3: '#FF8CBA',
      lighten4: '#FFB8D2',
      lighten5: '#FFE3EC',
      darken1: '#BC0A6F',
      darken2: '#A30664',
      darken3: '#870557',
      darken4: '#620042'

    },
    error: {
      base: '#E12D39',
      lighten1: '#EF4E4E',
      lighten2: '#F86A6A',
      lighten3: '#FF9B9B',
      lighten4: '#FFBDBD',
      lighten5: '#FFE3E3',
      darken1: '#CF1124',
      darken2: '#AB091E',
      darken3: '#8A041A',
      darken4: '#610316'
    },
    success: {
      base: '#27AB83',
      lighten1: '#3EBD93',
      lighten2: '#65D6AD',
      lighten3: '#8EEDC7',
      lighten4: '#C6F7E2',
      lighten5: '#EFFCF6',
      darken1: '#199473',
      darken2: '#147D64',
      darken3: '#0C6B58',
      darken4: '#014D40'
    },
    grey: {
      base: '#627D98',
      lighten1: '#829AB1',
      lighten2: '#9FB3C8',
      lighten3: '#BCCCDC',
      lighten4: '#D9E2EC',
      lighten5: '#F0F4F8',
      darken1: '#486581',
      darken2: '#334E68',
      darken3: '#243B53',
      darken4: '#102A43'
    }
  }
})

// window.Vue = Vue
window.Vue.use(Vuetify)
window.Vue.use(permissions)
window.Vue.use(snackbar)
window.Vue.use(confirm)

window.Vue.component('example-component', ExampleComponent)
window.Vue.component('tasks', Tasks)
window.Vue.component('tags', Tags)
window.Vue.component('taskstailwind', TasksTailwind)
window.Vue.component('tasques', Tasques)
window.Vue.component('login-form', LoginForm)
window.Vue.component('register-form', RegisterForm)
// window.Vue.component('editable-form', EditableForm)
window.Vue.component('user-list', UserList)
window.Vue.component('user-select', UserSelect)
window.Vue.component('impersonate', Impersonate)
window.Vue.component('git-info', GitInfo)
window.Vue.component('tema', Tema)
window.Vue.component('profile', Profile)
window.Vue.component('changelog', Changelog)
window.Vue.component('navigation', Navigation)
window.Vue.component('service-worker', ServiceWorker)
// window.Vue.component('service-worker', ServiceWorker)
// Notifications
window.Vue.component('notifications', Notifications)
window.Vue.component('notifications-widget', NotificationsWidget)
window.Vue.component('user-select-component', UserSelectComponent)
window.Vue.component('data-iterator-tasks', DataIteratorTasks)
window.Vue.component('data-table-tasks', DataTableTasks)

// Changelog
// window.Vue.component('reset-password-form', ResetPasswordForm)

// window.Vue.component('snackbar', Snackbar)

// eslint-disable-next-line no-unused-vars
const app = new window.Vue(AppComponent)
