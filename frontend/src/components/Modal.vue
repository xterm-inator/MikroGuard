<template>
  <div class="modal blur fade" tabindex="-1" role="dialog" ref="root">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">{{ props.title }}</h2>

          <slot name="close" v-bind="{ close }">
            <button type="button" aria-label="Close" class="btn-close" @click="close"></button>
          </slot>
        </div>

        <div class="modal-body">
          <slot />
        </div>

        <div class="modal-footer">
          <slot name="footer" v-bind="{ close }"/>
        </div>
      </div>
    </div>
  </div>

</template>
<script setup lang="ts">
import { Modal } from 'bootstrap'
import { onMounted, ref } from 'vue'

interface Props {
  title: string
}

const props = defineProps<Props>()
const root = ref<HTMLElement | null>(null);
let modal: Modal|null = null
const emit = defineEmits(['open'])

onMounted(() => {
  if (root.value) {
    modal = new Modal(root.value)

  }
})

const open = () => {
  if (modal) {
    modal.show()
    emit('open')
  }
}

const close = () => {
  if (modal) {
    modal.hide()
  }
}


defineExpose({
  open,
  close
})
</script>
