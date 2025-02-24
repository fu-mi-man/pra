<!-- @/components/molecules/overlays/LoadingOverlay.vue -->
<template>
 <v-overlay
   :value="value"
   absolute
 >
   <v-progress-circular
     :size="size"
     indeterminate
   />
 </v-overlay>
</template>

<script>
/**
* ローディング状態を表示するオーバーレイコンポーネント
* 親要素に対して絶対配置で表示され、中央にローディングインジケーターを表示する
*
* @example
* <loading-overlay
*   :value="loading"
*   :size="64"
* />
*/
export default {
 name: 'LoadingOverlay',
 props: {
   /** オーバーレイの表示/非表示を制御する値 */
   value: {
     type: Boolean,
     required: true
   },
   /**
    * ローディングインジケーターのサイズ
    * @default 96
    */
   size: {
     type: [Number, String],
     default: 96
   },
   /**
    * ローディングインジケーターの色
    * Vuetifyのcolor値を指定可能（primary, secondary, error など）
    * @default primary
    */
  //  color: {
  //    type: String,
  //    default: 'primary'
  //  }
 }
}
</script>
