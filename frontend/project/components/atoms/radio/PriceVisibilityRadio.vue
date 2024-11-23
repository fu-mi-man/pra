<!-- components/atoms/radios/PriceVisibilityRadio.vue -->
<template>
  <v-radio-group
    :value="value"
    row
    hide-details
    @change="$emit('input', $event)"
  >
    <v-radio
      label="価格を表示"
      value="visible"
    />
    <v-radio
      label="価格を非表示"
      value="hidden"
      class="ml-4"
    />
  </v-radio-group>
</template>

<script>
export default {
  name: 'PriceVisibilityRadio',
  props: {
    value: {
      type: String,
      default: 'visible'
    }
  }
}
</script>
