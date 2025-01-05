<!-- pages/announcements/create.vue -->
<template>
  <v-container>
    <v-card max-width="800" class="mx-auto">
      <v-card-title> お知らせ作成 </v-card-title>
      <v-alert v-if="errors.length > 0" type="error" dense class="mb-4">
        <ul class="mb-0 pl-4">
          <li v-for="(error, index) in errors" :key="index">
            {{ error }}
          </li>
        </ul>
      </v-alert>
      <v-card-text>
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
                :disabled-date="(date) => isBeforeDate(date, new Date())"
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
      </v-card-text>
      <v-card-actions class="justify-end">
        <v-btn color="primary" @click="submit"> 保存 </v-btn>
      </v-card-actions>
    </v-card>

    <!-- Snackbar -->
    <v-snackbar v-model="snackbar.show" :color="snackbar.color">
      {{ snackbar.text }}
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar.show = false">
          閉じる
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
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
      valid: false,               // フォームのバリデーション状態
      formData: {
        title: '',                // タイトル
        category: '',             // カテゴリ
        is_published: true,       // 公開状態
        start_at: null,           // 公開開始日時
        end_at: null,             // 公開終了日時
        show_banner: false,       // バナー表示フラグ
        banner_start_at: null,    // バナー表示開始日時
        banner_end_at: null,      // バナー表示終了日時
        send_notification: false, // メール通知フラグ
        content: '',              // 本文
      },
      // カテゴリの選択肢
      categories: [
        { label: 'お知らせ', value: 'notice' },
        { label: 'メンテナンス', value: 'maintenance' },
        { label: 'アップデート', value: 'update' },
      ],
      snackbar: {
        show: false,
        text: '',
        color: 'success',
      },
      errors: [],
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
    /**
     * 2つの日付を比較し，targetDateが基準日より前かどうかを判定
     * @param {Date} targetDate - 比較対象の日付
     * @param {Date} baseDate - 基準となる日付
     * @returns {boolean} targetDateが基準日より前の場合true
     */
    isBeforeDate(targetDate, baseDate) {
      const target = new Date(targetDate)
      const reference = new Date(baseDate)

      // 日付のみを比較するため時刻部分を削除
      target.setHours(0, 0, 0, 0)
      reference.setHours(0, 0, 0, 0)

      return target.getTime() < reference.getTime()
    },
    validateDates() {
      const errors = []

      // 公開期間の前後関係チェック
      if (this.formData.start_at && this.formData.end_at) {
        if (new Date(this.formData.end_at) <= new Date(this.formData.start_at)) {
          errors.push('公開終了日時は開始日時より後の日時を指定してください')
        }
      }

      // バナー表示がONの場合のチェック
      if (this.formData.show_banner) {
        // バナー期間の前後関係チェック
        if (this.formData.banner_start_at && this.formData.banner_end_at) {
          if (new Date(this.formData.banner_end_at) <= new Date(this.formData.banner_start_at)) {
            errors.push(
              'バナー終了日時は開始日時より後の日時を指定してください'
            )
          }
        }

        // バナー開始日時は公開開始日時以降であること
        if (this.formData.start_at && this.formData.banner_start_at) {
          if (
            new Date(this.formData.banner_start_at) <
            new Date(this.formData.start_at)
          ) {
            errors.push('バナー開始日時は公開開始日時以降を指定してください')
          }
        }
      }

      return errors
    },
    async submit() {
      if (!this.$refs.form.validate()) return

      this.errors = this.validateDates()
      if (this.errors.length > 0) return

      try {
        await this.$axios.post('/api/announcements', this.formData)

        this.snackbar = {
          show: true,
          text: 'お知らせを作成しました',
          color: 'success',
        }

        this.$router.push('/list')
      } catch (error) {
        console.error('エラー:', error.response?.data || error.message)
        this.snackbar = {
          show: true,
          text: 'お知らせの作成に失敗しました',
          color: 'error',
        }
      }
    },
  },
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
