<!-- @/components/atoms/datepicker/AnnouncementDatePicker.vue -->
<template>
  <date-picker
    v-bind="$attrs"
    :value="value"
    type="datetime"
    format="YYYY-MM-DD HH:mm:ss"
    value-type="format"
    confirm
    @input="$emit('input', $event)"
  />
</template>

<script>
export default {
  name: 'AnnouncementDatePicker',
  inheritAttrs: false,

  props: {
    value: {
      type: String,
      default: null
    }
  }
}
</script>
