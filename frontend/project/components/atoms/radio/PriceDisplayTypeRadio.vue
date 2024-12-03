<!-- components/atoms/radios/PriceDisplayRadio.vue -->
<template>
  <v-radio-group
    :value="value"
    :row="row"
    hide-details="auto"
    @change="$emit('input', $event)"
  >
    <v-radio
      :class="{
        'mr-8': row,
        'mb-3': !row
      }"
      label="価格を表示"
      value="visible"
    />
    <v-radio
      label="価格を非表示"
      value="hidden"
    />
  </v-radio-group>
</template>

<script>
export default {
  name: 'PriceDisplayRadio',
  props: {
    /**
     * ラジオボタンの選択値
     * @values 'visible' | 'hidden'
     */
    value: {
      type: String,
      default: 'visible'
    },
    /**
     * ラジオボタンを横並びにするか
     * true: 横並び, false: 縦並び
     */
    row: {
      type: Boolean,
      default: true
    },
  }
}
</script>
