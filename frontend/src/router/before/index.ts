import auth from './auth'
import type { Router } from 'vue-router'

export default (router: Router) => {
  router.beforeEach(auth)
}
