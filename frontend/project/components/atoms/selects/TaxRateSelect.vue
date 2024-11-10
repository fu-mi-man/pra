<template>
  <div>
    <v-select
      :value="value"
      :items="taxRates"
      dense
      outlined
      hide-details
      class="tax-rate-select"
      style="max-width: 150px"
      @input="$emit('input', $event)"
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
