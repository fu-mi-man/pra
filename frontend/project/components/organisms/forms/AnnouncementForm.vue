<!-- components/organisms/forms/AnnouncementForm.vue -->
<template>
  <v-form ref="form" v-model="valid">
    <!-- タイトル -->
    <validated-text-field
      v-model="formData.title"
      class="mb-4"
      :required="true"
      :input-max-length="30"
      label="タイトル"
      maxlength="30"
      counter
      dense
      outlined
      clearable
      clear-icon="mdi-close-circle"
      hide-details="auto"
      placeholder="タイトルを入力してください"
    />
    <!-- カテゴリ -->
    <validated-select-field
      v-model="formData.category"
      class="mb-4"
      :items="categories"
      :required="true"
      label="カテゴリ"
      item-text="label"
      item-value="value"
      dense
      outlined
      hide-details="auto"
      placeholder="カテゴリを選択してください"
    />
    <!-- 公開ステータス -->
    <v-switch
      v-model="formData.is_published"
      class="mb-4"
      :label="publishLabel"
      color="primary"
      inset
      hide-details="auto"
    />
    <!-- 公開開始日時と終了日時 -->
    <v-row class="mb-4">
      <v-col cols="12" sm="6">
        <announcement-date-picker
          v-model="formData.start_at"
          class="announcement-form__date-picker"
          :disabled-date="(date) => isBeforeDate(date, new Date())"
          placeholder="公開開始日時を選択"
        />
      </v-col>
      <v-col cols="12" sm="6">
        <announcement-date-picker
          v-model="formData.end_at"
          class="announcement-form__date-picker"
          :disabled-date="(date) => isBeforeDate(date, formData.start_at)"
          placeholder="公開終了日時を選択"
        />
      </v-col>
    </v-row>
    <!-- バナー表示スイッチ -->
    <v-switch
      v-model="formData.show_banner"
      class="mb-4"
      :label="bannerLabel"
      color="primary"
      inset
      hide-details="auto"
    />
    <!-- バナー表示期間 -->
    <v-row v-if="formData.show_banner" class="mb-4">
      <v-col cols="12" sm="6">
        <announcement-date-picker
          v-model="formData.banner_start_at"
          class="announcement-form__banner-picker"
          :disabled-date="(date) => isBeforeDate(date, formData.start_at)"
          placeholder="表示開始日時を選択"
        />
      </v-col>
      <v-col cols="12" sm="6">
        <announcement-date-picker
          v-model="formData.banner_end_at"
          class="announcement-form__banner-picker"
          :disabled-date="(date) => isBeforeDate(date, formData.banner_start_at)"
          placeholder="表示終了日時を選択"
        />
      </v-col>
    </v-row>
    <!-- メール通知 -->
    <v-checkbox
      v-model="formData.send_notification"
      class="mb-4"
      label="ユーザーにメールで通知する"
      color="primary"
      hide-details="auto"
    />
    <!-- お知らせ本文 -->
    <validated-textarea-field
      v-model="formData.content"
      label="お知らせ本文"
      :required="true"
      :input-max-length="1000"
      auto-grow
      rows="6"
      maxlength="1000"
      counter
      outlined
      clearable
      clear-icon="mdi-close-circle"
      hide-details="auto"
      placeholder="お知らせを入力してください"
    />
  </v-form>
</template>

<script>
import AnnouncementDatePicker from '@/components/atoms/datepicker/AnnouncementDatePicker.vue'
import ValidatedSelectField from '@/components/atoms/inputs/base/ValidatedSelectField.vue'
import ValidatedTextareaField from '@/components/atoms/inputs/base/ValidatedTextareaField.vue'
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'

export default {
  components: {
    AnnouncementDatePicker,
    ValidatedSelectField,
    ValidatedTextareaField,
    ValidatedTextField,
  },
  data() {
    return {
      valid: false,
      formData: {
        title: '',
        category: '',
        is_published: true,
        start_at: null,
        end_at: null,
        show_banner: false,
        banner_start_at: null,
        banner_end_at: null,
        send_notification: false,
        content: '',
      },
      categories: [
        { label: 'お知らせ', value: 'notice' },
        { label: 'メンテナンス', value: 'maintenance' },
        { label: 'アップデート', value: 'update' },
      ],
    }
  },
  computed: {
    publishLabel() {
      return `${this.formData.is_published ? '公開する' : '公開しない'}`
    },
    bannerLabel() {
      return `バナーを${this.formData.show_banner ? '表示する' : '表示しない'}`
    },
  },
  methods: {
    isBeforeDate(targetDate, baseDate) {
      const target = new Date(targetDate)
      const reference = new Date(baseDate)
      target.setHours(0, 0, 0, 0)
      reference.setHours(0, 0, 0, 0)
      return target.getTime() < reference.getTime()
    },
    validateDates() {
      const errors = []
      if (this.formData.start_at && this.formData.end_at) {
        if (new Date(this.formData.end_at) <= new Date(this.formData.start_at)) {
          errors.push('公開終了日時は開始日時より後の日時を指定してください')
        }
      }
      if (this.formData.show_banner) {
        if (this.formData.banner_start_at && this.formData.banner_end_at) {
          if (new Date(this.formData.banner_end_at) <= new Date(this.formData.banner_start_at)) {
            errors.push('バナー終了日時は開始日時より後の日時を指定してください')
          }
        }
        if (this.formData.start_at && this.formData.banner_start_at) {
          if (new Date(this.formData.banner_start_at) < new Date(this.formData.start_at)) {
            errors.push('バナー開始日時は公開開始日時以降を指定してください')
          }
        }
      }
      return errors
    },
    validate() {
      if (!this.$refs.form.validate()) return false

      const errors = this.validateDates()
      if (errors.length > 0) {
        this.$emit('validation-error', errors)
        return false
      }

      return true
    },
    async submit() {
      if (!(await this.validate())) return
      this.$emit('submit', this.formData)
    }
  }
}
</script>

<style lang="scss" scoped>
.announcement-form {
  &__date-picker,
  &__banner-picker {
    width: 100%;
    :deep(.mx-input) {
      height: 40px;
      border-radius: 4px;
    }
  }
}
</style>
