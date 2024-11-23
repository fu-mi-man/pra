<!-- components/atoms/radios/PriceTypeRadio.vue -->
<template>
  <v-radio-group
    :value="value"
    :disabled="disabled"
    row
    hide-details
    @change="$emit('input', $event)"
  >
    <v-radio
      label="税別価格で入力"
      value="excluded"
    ></v-radio>
    <v-radio
      label="税込価格で入力"
      value="included"
      class="ml-4"
    ></v-radio>
  </v-radio-group>
</template>

<script>
export default {
  name: 'PriceTypeRadio',
  props: {
    value: {
      type: String,
      default: 'excludingTax',
    },
    disabled: {
      type: Boolean,
      default: false
    }
  }
}
</script>
