<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 512 512"
    fill="none"
    :width="size"
    :height="size"
    :class="['mikroguard-logo-icon', customClass]"
    aria-label="MikroGuard Logo"
  >
    <defs>
      <linearGradient :id="`mgShieldGrad-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#1e40af" />
        <stop offset="50%" stop-color="#1e293b" />
        <stop offset="100%" stop-color="#0f172a" />
      </linearGradient>
      <linearGradient :id="`mgBorderGrad-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#38bdf8" />
        <stop offset="45%" stop-color="#2563eb" />
        <stop offset="100%" stop-color="#6366f1" />
      </linearGradient>
      <linearGradient :id="`mgNeonGrad-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#00f0ff" />
        <stop offset="50%" stop-color="#0066ff" />
        <stop offset="100%" stop-color="#7000ff" />
      </linearGradient>
      <linearGradient :id="`mgWingLeft-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#38bdf8" />
        <stop offset="100%" stop-color="#1d4ed8" />
      </linearGradient>
      <linearGradient :id="`mgWingRight-${uid}`" x1="100%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#818cf8" />
        <stop offset="100%" stop-color="#2563eb" />
      </linearGradient>
      <linearGradient :id="`mgCoreGlow-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#ffffff" />
        <stop offset="100%" stop-color="#38bdf8" />
      </linearGradient>
      <filter :id="`mgDropShadow-${uid}`" x="-20%" y="-20%" width="140%" height="140%">
        <feDropShadow dx="0" dy="12" stdDeviation="16" flood-color="#0066ff" flood-opacity="0.3" />
      </filter>
      <filter :id="`mgGlow-${uid}`" x="-30%" y="-30%" width="160%" height="160%">
        <feGaussianBlur stdDeviation="6" result="blur" />
        <feComposite in="SourceGraphic" in2="blur" operator="over" />
      </filter>
    </defs>

    <!-- Outer Shield Base -->
    <path
      d="M 256 36 C 362 36 432 76 432 168 C 432 308 334 408 256 476 C 178 408 80 308 80 168 C 80 76 150 36 256 36 Z"
      :fill="`url(#mgShieldGrad-${uid})`"
      :filter="`url(#mgDropShadow-${uid})`"
    />

    <!-- Shield Inner Border Highlight -->
    <path
      d="M 256 46 C 354 46 418 82 418 168 C 418 298 326 392 256 456 C 186 392 94 298 94 168 C 94 82 158 46 256 46 Z"
      fill="none"
      :stroke="`url(#mgBorderGrad-${uid})`"
      stroke-width="8"
      stroke-linecap="round"
      stroke-linejoin="round"
    />

    <!-- Stylized M-Wings -->
    <path
      d="M 144 164 L 144 268 C 144 316 190 364 228 392 L 228 290 L 194 252 L 194 186 Z"
      :fill="`url(#mgWingLeft-${uid})`"
    />
    <path
      d="M 368 164 L 368 268 C 368 316 322 364 284 392 L 284 290 L 318 252 L 318 186 Z"
      :fill="`url(#mgWingRight-${uid})`"
    />
    <path
      d="M 144 164 L 256 250 L 368 164 L 328 128 L 256 186 L 184 128 Z"
      :fill="`url(#mgNeonGrad-${uid})`"
    />

    <!-- WireGuard Tunnel Core Network Rings & Nexus -->
    <circle cx="256" cy="256" r="32" fill="#0f172a" :stroke="`url(#mgBorderGrad-${uid})`" stroke-width="6" />
    <circle cx="256" cy="256" r="16" :fill="`url(#mgCoreGlow-${uid})`" :filter="`url(#mgGlow-${uid})`" />
    <circle cx="256" cy="256" r="8" fill="#0284c7" />

    <!-- Network Interconnect Nodes -->
    <circle cx="144" cy="164" r="10" fill="#38bdf8" stroke="#ffffff" stroke-width="3" />
    <circle cx="368" cy="164" r="10" fill="#38bdf8" stroke="#ffffff" stroke-width="3" />
    <circle cx="256" cy="100" r="8" fill="#00f0ff" />
    <circle cx="256" cy="410" r="7" fill="#38bdf8" />

    <!-- Connection / Tunnel Rays -->
    <line x1="256" y1="108" x2="256" y2="128" stroke="#00f0ff" stroke-width="4" stroke-linecap="round" opacity="0.8" />
    <line x1="256" y1="288" x2="256" y2="402" stroke="#38bdf8" stroke-width="4" stroke-dasharray="6,6" stroke-linecap="round" opacity="0.7" />
  </svg>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    size?: number | string
    class?: string
  }>(),
  {
    size: 28,
    class: ''
  }
)

const customClass = computed(() => props.class)
const uid = Math.random().toString(36).substring(2, 9)
</script>
