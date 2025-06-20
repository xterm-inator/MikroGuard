<template>
  <div class="row row-cards" v-if="config">
    <div class="col-12">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <div class="page-pretitle">Connection</div>
          <h2 class="page-title">{{ config.peer_name }}</h2>
        </div>
        <!-- Page title actions -->
        <div class="col-12 col-md-auto ms-auto d-print-none">
          <div class="btn-list">
            <button class="btn btn-white d-sm-inline-block" @click="handleDownload">Download</button>
            <button class="btn btn-danger d-sm-inline-block" @click="handleDelete">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-3">
      <stats-card title="Rx" :value="prettyBytes(config.rx)" />
    </div>
    <div class="col-md-6 col-xl-3">
      <stats-card title="Tx" :value="prettyBytes(config.tx)" />
    </div>
    <div class="col-md-6 col-xl-3">
      <stats-card title="Last Handshake" :value="lastHandshake" />
    </div>
    <div class="col-md-6 col-xl-3">
      <stats-card title="Last Connected From" :value="config.last_connected_from" />
    </div>
    <div class="col-12">
      <div class="card">
        <ul class="nav nav-tabs nav-tabs-alt" data-bs-toggle="tabs" role="tablist">
          <li class="nav-item">
            <a href="#tabs-details" class="nav-link active" data-bs-toggle="tab" role="tab" tabindex="-1">
              <network-icon class="me-2"/>
              Details</a>
          </li>
          <li class="nav-item">
            <a href="#tabs-config" class="nav-link" data-bs-toggle="tab" role="tab">
              <file-description-icon class="me-2"/>
              Config</a>
          </li>
          <li class="nav-item">
            <a href="#tabs-qrcode" class="nav-link" data-bs-toggle="tab" role="tab">
              <qrcode-icon class="me-2"/>
              QRCode</a>
          </li>
        </ul>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane active show" id="tabs-details" role="tabpanel">
              <div class="datagrid" v-if="config">
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Name</div>
                  <div class="datagrid-content">{{ config.peer_name }}</div>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Tunnel Address</div>
                  <div class="datagrid-content">{{ config.address }}</div>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer DNS</div>
                  <div class="datagrid-content">{{ config.dns }}</div>
                </div>

                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Public Key</div>
                  <hidden class="datagrid-content">{{ config.peer_public_key }}</hidden>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Peer Private Key</div>
                  <hidden class="datagrid-content">{{ config.peer_private_key }}</hidden>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Preshared Key</div>
                  <hidden class="datagrid-content">{{ config.peer_preshared_key }}</hidden>
                </div>
                <div class="datagrid-item">
                  <div class="datagrid-title">Server Public Key</div>
                  <hidden class="datagrid-content">{{ config.server_public_key }}</hidden>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tabs-config" role="tabpanel">
              <div>
                <pre>{{ configString }}</pre>
              </div>
            </div>
            <div class="tab-pane" id="tabs-qrcode" role="tabpanel">
              <div class="text-center">
                <qrcode-vue :value="configString" :size="300" level="H" render-as="svg" :margin="10"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { NetworkIcon, FileDescriptionIcon, QrcodeIcon } from 'vue-tabler-icons'
import QrcodeVue from 'qrcode.vue'
import { generateString } from '@/utils/config-string-generator'
import { useConfigStore } from '@/stores/config'
import { computed, onMounted, ref } from 'vue'
import StatsCard from '@/components/StatsCard.vue'
import prettyBytes from 'pretty-bytes'
import swal from 'sweetalert'
import JSZip from 'jszip'
import { saveAs } from 'file-saver'
import { kebabCase } from 'lodash'
import Hidden from '@/components/Hidden.vue'
import dayjs from 'dayjs'

interface Props {
  id: string
}

const props = defineProps<Props>()

const configStore = useConfigStore()

let configString = ref<string>('')

const config = computed(() => configStore.config)

const lastHandshake = computed(() => {
  if (configStore.config && configStore.config.last_handshake) {
    return dayjs.utc(configStore.config.last_handshake).local().fromNow()
  }

  return '-'
})

onMounted(() => {
  if (configStore.config) {
    configString.value = generateString(configStore.config)
  }
})

async function handleDownload(): Promise<void> {
  if (configStore.config) {
    const configName = kebabCase(configStore.config.server_name)
    let zip = new JSZip();
    zip.file(`${configName}.conf`, configString.value)
    const content = await zip.generateAsync({ type: 'blob' })
    saveAs(content, `${configName}.zip`)
  }
}

async function handleDelete(): Promise<void> {
  const response = await swal({
    title: 'Are you sure?',
    text: 'This will remove all WireGuard settings from the router for this user.',
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
    await configStore.deleteConfig(props.id)
    configStore.resetConfig()
    if (swal.stopLoading && swal.close) {
      swal.stopLoading()
      swal.close()
    }
  }
}
</script>
