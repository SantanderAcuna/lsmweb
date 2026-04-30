import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { VueQueryPlugin } from '@tanstack/vue-query'
import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import './assets/scss/app.scss'
import 'bootstrap'
import { FontAwesomeIcon } from './plugins/fontawesome'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.component('FaIcon', FontAwesomeIcon)
app.use(createPinia())
app.use(router)
app.use(VueQueryPlugin, {
  queryClientConfig: {
    defaultOptions: {
      queries: {
        staleTime: 60_000,
        retry: 1,
        refetchOnWindowFocus: false
      }
    }
  }
})
app.use(Toast, {
  position: POSITION.TOP_RIGHT,
  timeout: 4000,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: false,
  hideProgressBar: false
})

app.mount('#app')
