<template>
  <div>
    <v-select
      :value="value"
      @input="$emit('input', $event)"
      :items="taxRates"
      dense
      outlined
      hide-details
      class="tax-rate-select"
      style="max-width: 150px"
    />
  </div>
</template>

<script>
export default {
  name: 'TaxRateSelect',
  props: {
    value: {
      type: Number,
      default: 10
    }
  },
  data() {
    return {
      taxRates: [
        { text: '8%', value: 8 },
        { text: '10%', value: 10 }
      ]
    }
  }
}
</script>
