<!-- pages/announcements/create.vue -->
<template>
  <v-container>
    <v-card max-width="800" class="mx-auto">
      <v-card-title> お知らせ作成 </v-card-title>

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
              <date-picker
                v-model="formData.start_at"
                class="announcement-form__date-picker"
                type="datetime"
                format="YYYY-MM-DD HH:mm:ss"
                value-type="YYYY-MM-DD HH:mm:ss"
                :disabled-date="date => isBeforeDate(date, new Date())"
                placeholder="公開開始日時を選択"
              />
            </v-col>
            <v-col cols="12" sm="6">
              <date-picker
                v-model="formData.end_at"
                class="announcement-form__date-picker"
                type="datetime"
                format="YYYY-MM-DD HH:mm:ss"
                value-type="YYYY-MM-DD HH:mm:ss"
                :disabled-date="date => isBeforeDate(date, formData.start_at)"
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
              <date-picker
                v-model="formData.banner_start_at"
                class="announcement-form__banner-picker"
                type="datetime"
                format="YYYY-MM-DD HH:mm:ss"
                value-type="YYYY-MM-DD HH:mm:ss"
                :disabled-date="date => isBeforeDate(date, new Date())"
                placeholder="表示開始日時を選択"
              />
            </v-col>
            <v-col cols="12" sm="6">
              <date-picker
                v-model="formData.banner_end_at"
                class="announcement-form__banner-picker"
                type="datetime"
                format="YYYY-MM-DD HH:mm:ss"
                value-type="YYYY-MM-DD HH:mm:ss"
                :disabled-date="date => isBeforeDate(date, formData.banner_start_at)"
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
  </v-container>
</template>

<script>
import ValidatedSelectField from '@/components/atoms/inputs/base/ValidatedSelectField.vue'
import ValidatedTextareaField from '@/components/atoms/inputs/base/ValidatedTextareaField.vue'
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'

export default {
  components: {
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
      if (!targetDate) return false
      if (!baseDate) return false

      const target = new Date(targetDate)
      const reference = new Date(baseDate)

      // 日付のみを比較するため時刻部分を削除
      target.setHours(0, 0, 0, 0)
      reference.setHours(0, 0, 0, 0)

      return target.getTime() < reference.getTime()
    },
    async submit() {
      if (!this.$refs.form.validate()) return

      try {
        const response = await this.$axios.post('/api/announcements', this.formData)
        console.log('APIレスポンス:', response.data)
        // 成功時の処理（例：メッセージ表示など）

      } catch (error) {
        console.error('エラー:', error.response?.data || error.message)
        alert(error.message)
        // エラー時の処理
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
