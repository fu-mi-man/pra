<template>
  <v-dialog
    :value="value"
    max-width="600px"
    persistent
    @input="$emit('input', $event)"
  >
    <v-card>
      <!-- ローディングオーバーレイ -->
      <v-overlay
        :value="loading"
        absolute
      >
        <v-progress-circular
          indeterminate
          size="64"
        />
      </v-overlay>

      <v-card-title>
        <span class="text-h5">
          カテゴリを編集
        </span>
      </v-card-title>

      <v-card-text>
        <!-- バリデーションエラーメッセージ -->
        <v-alert
          v-if="validationErrors.length > 0"
          class="mb-4"
          dense
          type="error"
        >
          <ul class="mb-0 pl-4">
            <li v-for="(error, index) in validationErrors" :key="index">
              {{ error }}
            </li>
          </ul>
        </v-alert>

        <v-form ref="form" v-model="isFormValid" @submit.prevent="save">
          <v-container>
            <v-row>
              <v-col cols="12">
                <validated-text-field
                  v-model="editedItem.name"
                  label="カテゴリ名"
                  :required="true"
                  :input-max-length="30"
                  :error-messages="errorMessages"
                />
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn
          :disabled="loading"
          color="grey darken-1"
          text
          @click="close">
          キャンセル
        </v-btn>
        <v-btn
          :disabled="!isFormValid || loading"
          color="primary"
          form="categoryForm"
          text
          type="submit"
          @click="save"
        >
          保存
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'

/**
 * カテゴリ編集用ダイアログコンポーネント
 * カテゴリの新規作成・編集に使用される
 */
export default {
  components: {
    ValidatedTextField,
  },
  props: {
    /** ダイアログの表示/非表示を制御するフラグ */
    value: {
      type: Boolean,
      default: false,
    },
    /** 編集対象のカテゴリ情報 */
    item: {
      type: Object,
      default: () => ({
        id: null,
        name: '',
      }),
      // コンソールにエラーが出る程度であってもなくてもどっちでもいい
      validator: (value) => {
        return 'id' in value && 'name' in value
      },
    },
    /** カテゴリの種類（document または product） */
    categoryType: {
      type: String,
      required: true,
      // コンソールにエラーが出る程度であってもなくてもどっちでもいい
      validator: (value) => ['document', 'product'].includes(value),
    },
  },
  data() {
    return {
      loading: false,     // ローディング
      isFormValid: false, // フォームの検証状態
      editedItem: {
        id: null,
        name: '',
      },
      validationErrors: [], // APIからのバリデーションエラーメッセージ
      errorMessages: [],    // 入力フィールドのエラーメッセージ
    }
  },
  computed: {
    categoryTypeLabel() {
      return this.categoryType === 'document' ? '文書カテゴリ' : '商品カテゴリ'
    }
  },
  watch: {
    /** 編集対象アイテムの変更を監視し、フォームの値を更新 */
    item: {
      handler(newVal) {
        this.editedItem = { ...newVal }
      },
      deep: true,
    },
  },
  methods: {
    /**
     * フォームの保存処理
     * バリデーションが成功した場合のみ実行される
     */
    async save() {
    // save() {
      if (!this.isFormValid) return

      try {
        this.loading = true
        // APIをコールする処理をここに実装
        // await this.updateCategoryAPI(this.editedItem);
        // テスト用コード（2秒スリープ・仮API）
        await new Promise(resolve => setTimeout(resolve, 2000))

        this.$emit('edited', {
          item: this.editedItem,
          success: true,
          message: `${this.categoryTypeLabel}「${this.editedItem.name}」を更新しました`
        })
        this.close()
      } catch (error) {
        if (error.response?.status === 422) {
          const errors = error.response.data.errors
          this.validationErrors = Object.values(errors).flat()
        } else {
          this.$emit('edited', {
            item: this.editedItem,
            success: false,
            message: '保存に失敗しました。再度お試しください。'
          })
        }
      } finally {
        this.loading = false
      }
    },
    /**
     * ダイアログを閉じる
     * フォームの状態をリセットし，編集データをクリアする
     */
    close() {
      this.errorMessages = []
      this.validationErrors = []
      this.$emit('input', false) // ダイアログを閉じる

      // ダイアログが完全に閉じた後にリセット処理を実行
      this.$nextTick(() => {
        if (this.$refs.form) {
          this.$refs.form.reset()
        }
        this.errorMessages = []
        this.editedItem = {
          id: null,
          name: '',
        }
        this.loading = false
      })
    },
  },
}
</script>
