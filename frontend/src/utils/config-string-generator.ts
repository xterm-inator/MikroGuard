import type { Config } from '@/stores/config'

const generateString = (config: Config) => {
  return`[Interface]
#${config.peer_name}
PrivateKey=${config.peer_private_key}
Address=${config.address}
DNS=${config.dns}

[Peer]
#${config.server_name}
PresharedKey=${config.peer_preshared_key}
PublicKey=${config.server_public_key}
Endpoint=${config.endpoint}
AllowedIPs=${config.allowed_ips}
`
}

export { generateString }
