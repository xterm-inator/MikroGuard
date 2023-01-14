export interface User {
  id?: string
  role: string
  email: string
  data_up?: number
  data_down?: number
  last_handshake?: Date,
  rx?: number,
  tx?: number,
}

export const DefaultUser: User = {
  email: '',
  role: 'user'
}
