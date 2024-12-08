<template>
  <v-container>
    <v-col>
      <v-row>
        <price-text-text-field
          v-model="generalPrice.priceText"
          :error.sync="validationErrors.generalPrice.priceText"
          @update:error="handleValidationError('priceText', $event)"
        />
        <price-note-text-field
          v-model="generalPrice.note"
          :error.sync="validationErrors.generalPrice.note"
          @input="handlePriceInput"
          @update:error="handleValidationError('note', $event)"
        />
      </v-row>
      <v-row class="mt-4">
        <v-btn
          color="primary"
          :disabled="isFormInvalid"
        >
          登録
        </v-btn>
      </v-row>
    </v-col>
  </v-container>
</template>

<script>
import PriceNoteTextField from '@/components/atoms/inputs/PriceNoteTextField.vue'
import PriceTextTextField from '@/components/atoms/inputs/PriceTextTextField.vue'

export default {
  components: {
    PriceNoteTextField,
    PriceTextTextField,
  },
  data() {
    return {
      generalPrice: {
        priceText: '',
        note: '',
      },
      validationErrors: {
        generalPrice: {
          priceText: false,
          note: false,
        },
      },
      number: 0,
    }
  },
  computed: {
    isFormInvalid() {
      // 必須項目のチェック
      const hasRequiredFields = this.generalPrice.priceText.trim() && this.generalPrice.note.trim()

      // バリデーションエラーのチェック
      const hasErrors = Object.values(this.validationErrors.generalPrice).some(error => error)

      return !hasRequiredFields || hasErrors
    }
  },
  methods: {
    handlePriceInput() {
      this.number++
    },
    handleValidationError(field, error) {
      this.validationErrors.generalPrice[field] = error
    }
  },
}
</script>
