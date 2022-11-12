export interface User {
  id?: string
  role: string
  email: string
  data_up?: number
  data_down?: number
  last_handshake?: Date
}

export const DefaultUser: User = {
  email: '',
  role: 'user'
}
