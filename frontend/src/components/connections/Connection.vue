<template>
  <div class="row row-cards" v-if="config">
    <div class="col-12">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="page-pretitle text-muted">Peer Connection</div>
          <h3 class="card-title fs-2 mb-0 fw-bold">{{ config.peer_name }}</h3>
        </div>
        <!-- Page title actions -->
        <div class="col-12 col-md-auto ms-auto d-print-none">
          <div class="btn-list">
            <button class="btn btn-outline-primary d-sm-inline-flex align-items-center" @click="handleDownload">
              <download-icon class="me-1" :size="16" />
              Download Config
            </button>
            <button class="btn btn-outline-danger d-sm-inline-flex align-items-center" @click="handleDelete">
              <trash-icon class="me-1" :size="16" />
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <stats-card title="Received (Rx)" :value="prettyBytes(config.rx)" badge-class="bg-green-lt">
        <template #icon>
          <arrow-down-icon :size="20"/>
        </template>
      </stats-card>
    </div>
    <div class="col-sm-6 col-xl-3">
      <stats-card title="Transmitted (Tx)" :value="prettyBytes(config.tx)" badge-class="bg-blue-lt">
        <template #icon>
          <arrow-up-icon :size="20"/>
        </template>
      </stats-card>
    </div>
    <div class="col-sm-6 col-xl-3">
      <stats-card title="Last Handshake" :value="lastHandshake" badge-class="bg-yellow-lt">
        <template #icon>
          <clock-icon :size="20"/>
        </template>
      </stats-card>
    </div>
    <div class="col-sm-6 col-xl-3">
      <stats-card title="Last Connected From" :value="config.last_connected_from || '-'" badge-class="bg-purple-lt">
        <template #icon>
          <world-icon :size="20"/>
        </template>
      </stats-card>
    </div>
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <ul class="nav nav-tabs nav-tabs-alt" data-bs-toggle="tabs" role="tablist">
          <li class="nav-item">
            <a :href="`#tabs-details-${config.id}`" class="nav-link active" data-bs-toggle="tab" role="tab" tabindex="-1">
              <network-icon class="me-2" :size="18"/>
              Details
            </a>
          </li>
          <li class="nav-item">
            <a :href="`#tabs-config-${config.id}`" class="nav-link" data-bs-toggle="tab" role="tab">
              <file-description-icon class="me-2" :size="18"/>
              Config
            </a>
          </li>
          <li class="nav-item">
            <a :href="`#tabs-qrcode-${config.id}`" class="nav-link" data-bs-toggle="tab" role="tab">
              <qrcode-icon class="me-2" :size="18"/>
              QR Code
            </a>
          </li>
        </ul>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane active show" :id="`tabs-details-${config.id}`" role="tabpanel">
              <div class="datagrid" v-if="config">
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Name</div>
                  <div class="datagrid-content fw-medium">{{ config.peer_name }}</div>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Tunnel Address</div>
                  <div class="datagrid-content">
                    <span class="badge bg-azure-lt font-monospace">{{ config.address }}</span>
                  </div>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer DNS</div>
                  <div class="datagrid-content font-monospace">{{ config.dns }}</div>
                </div>

                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Public Key</div>
                  <hidden class="datagrid-content font-monospace">{{ config.peer_public_key }}</hidden>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Private Key</div>
                  <hidden class="datagrid-content font-monospace">{{ config.peer_private_key }}</hidden>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Preshared Key</div>
                  <hidden class="datagrid-content font-monospace">{{ config.peer_preshared_key }}</hidden>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Server Public Key</div>
                  <hidden class="datagrid-content font-monospace">{{ config.server_public_key }}</hidden>
                </div>
              </div>
            </div>
            <div class="tab-pane" :id="`tabs-config-${config.id}`" role="tabpanel">
              <div class="p-3 bg-body-tertiary rounded">
                <pre class="m-0 font-monospace">{{ generateString(props.config) }}</pre>
              </div>
            </div>
            <div class="tab-pane" :id="`tabs-qrcode-${config.id}`" role="tabpanel">
              <div class="text-center py-3">
                <qrcode-vue :value="generateString(props.config)" :size="260" level="H" render-as="svg" :margin="10"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import { NetworkIcon, FileDescriptionIcon, QrcodeIcon, ArrowDownIcon, ArrowUpIcon, ClockIcon, WorldIcon, DownloadIcon, TrashIcon } from 'vue-tabler-icons'
import QrcodeVue from 'qrcode.vue'
import { generateString } from '@/utils/config-string-generator'
import { computed } from 'vue'
import StatsCard from '@/components/StatsCard.vue'
import prettyBytes from 'pretty-bytes'
import swal from 'sweetalert'
import JSZip from 'jszip'
import { saveAs } from 'file-saver'
import { kebabCase } from 'lodash'
import Hidden from '@/components/Hidden.vue'
import dayjs from 'dayjs'
import type { Config } from '@/stores/config'

interface Props {
  config: Config
  onDelete: (id: string) => Promise<void>
}

const props = defineProps<Props>()

const lastHandshake = computed(() => {
  if (props.config && props.config.last_handshake) {
    return dayjs.utc(props.config.last_handshake).local().fromNow()
  }

  return '-'
})

async function handleDownload(): Promise<void> {
  if (props.config) {
    const configName = kebabCase(props.config.server_name)
    let zip = new JSZip();
    zip.file(`${configName}.conf`, generateString(props.config))
    const content = await zip.generateAsync({ type: 'blob' })
    saveAs(content, `${configName}.zip`)
  }
}

async function handleDelete(): Promise<void> {
  const response = await swal({
    title: 'Are you sure?',
    text: 'This will remove all WireGuard settings from the router for this connection.',
    icon: 'warning',
    buttons: {
      cancel: true,
      ok: {
        text: 'Yes',
        className: 'swal-button--danger',
        closeModal: false
      }
    },
    dangerMode: true
  })
  if (response) {
    await props.onDelete(props.config.id)

    if (swal.stopLoading && swal.close) {
      swal.stopLoading()
      swal.close()
    }
  }
}
</script>
