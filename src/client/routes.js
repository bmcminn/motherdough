import Login from './views/Login.svelte'
import MainLayout from './layouts/Main.svelte'
// import MainLayout from './views/public/layout.svelte'
// import AdminLayout from './views/admin/layout.svelte'
// import AdminIndex from './views/admin/index.svelte'
// import EmployeesIndex from './views/admin/employees/index.svelte'

function userIsAdmin() {
  //check if user is admin and returns true or false
}

const routes = [
  { name: '/',      component: MainLayout,    },
  { name: 'login',  component: Login,           layout: MainLayout },
  // {
  //   name: 'admin',
  //   component: AdminLayout,
  //   onlyIf: { guard: userIsAdmin, redirect: '/login' },
  //   nestedRoutes: [
  //     { name: 'index', component: AdminIndex },
  //     {
  //       name: 'employees',
  //       component: '',
  //       nestedRoutes: [
  //         { name: 'index', component: EmployeesIndex },
  //         { name: 'show/:id', component: EmployeesShow },
  //       ],
  //     },
  //   ],
  // },
]

export { routes }
