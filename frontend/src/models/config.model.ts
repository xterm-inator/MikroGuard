export interface Config {
  peer_name: string
  peer_private_key: string
  peer_public_key: string
  peer_preshared_key: string
  server_name: string
  server_public_key: string
  endpoint: string
  dns: string
  allowed_ips: string
  address: string
  rx: number
  tx: number
  last_handshake: string|null
  last_connected_from: string
}
