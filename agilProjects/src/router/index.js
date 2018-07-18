import Vue from 'vue'
import Router from 'vue-router'
import App from '../App'
import Access from '@/components/Access'
import NewProject from '@/components/NewProject'
import seeExistingProject from '@/components/seeExistingProject'
import Profile from '@/components/Profile'

Vue.use(Router)

export default new Router({
  routes: [
    {
        path: '/',
        name: 'App',
        component: App
    },
    {
        path: '/createProject',
        name: 'NewProject',
        component: NewProject
    },
    {
      path: '/access',
      name: 'Access',
      component: Access
    },
    {
        path: '/projects/:id',
        name: 'seeExistingProject',
        component: seeExistingProject
    },
    {
        path: '/:username',
        name: 'Profile',
        component: Profile
    }
  ]
})
