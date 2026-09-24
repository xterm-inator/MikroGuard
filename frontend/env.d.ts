/// <reference types="vite/client" />

import type { NativeElements, ReservedProps, VNode } from '@vue/runtime-dom'

declare module '@vue/runtime-dom' {
  interface HTMLAttributes {
    [key: string]: any
  }
  interface SVGAttributes {
    [key: string]: any
  }
}

declare module 'vue' {
  interface HTMLAttributes {
    [key: string]: any
  }
  interface SVGAttributes {
    [key: string]: any
  }
}

declare global {
  namespace JSX {
    interface Element extends VNode {}
    interface ElementClass {
      $props: {}
    }
    interface ElementAttributesProperty {
      $props: {}
    }
    interface IntrinsicElements extends NativeElements {
      [name: string]: any
    }
    interface IntrinsicAttributes extends ReservedProps {}
  }
}
