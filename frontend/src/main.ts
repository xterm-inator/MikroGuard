import { createApp } from 'vue'
import { createPinia } from 'pinia'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import relativeTime from 'dayjs/plugin/relativeTime'

import App from './App.vue'
import router from './router'
import AsyncButton from '@/components/AsyncButton.vue'

import 'bootstrap'

dayjs.extend(utc)
dayjs.extend(relativeTime)

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.component('AsyncButton', AsyncButton)

app.mount('#app')
