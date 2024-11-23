<template>
  <div>
    <v-select
      :value="value"
      :items="taxRates"
      dense
      outlined
      hide-details
      class="consumption-tax__select"
      @input="$emit('input', $event)"
    />
  </div>
</template>

<script>
export default {
  name: 'ConsumptionTaxSelect',
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
