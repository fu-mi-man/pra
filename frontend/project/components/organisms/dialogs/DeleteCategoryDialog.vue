<!-- components/organisms/dialogs/DeleteCategoryDialog.vue -->
<template>
  <v-dialog
    :value="value"
    max-width="500px"
    persistent
    @input="$emit('input', $event)"
  >
    <v-card>
      <!-- ローディングオーバーレイ -->
      <loading-overlay :value="loading" />

      <v-card-title>
        <span class="text-h5">
          {{ categoryTypeLabel }} の削除確認
        </span>
      </v-card-title>

      <v-card-text>
        <!-- バリデーションエラーメッセージ -->
        <validation-error-alert :errors="validationErrors" />

        <p class="mb-0">
          {{ categoryTypeLabel }}「{{ item.name }}」を削除してもよろしいですか？
        </p>
      </v-card-text>

      <v-card-actions>
        <v-spacer />
        <v-btn
          :disabled="loading"
          color="grey darken-1"
          text
          @click="close"
        >
          キャンセル
        </v-btn>
        <v-btn
          :disabled="loading"
          color="error"
          text
          @click="deleteCategory"
        >
          削除
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import ValidationErrorAlert from '@/components/molecules/alerts/ValidationErrorAlert.vue'
import LoadingOverlay from '@/components/molecules/overlays/LoadingOverlay.vue'

/**
 * カテゴリ削除用ダイアログコンポーネント
 * カテゴリの削除前の確認に使用される
 */
export default {
  components: {
    ValidationErrorAlert,
    LoadingOverlay,
  },

  props: {
    /** ダイアログの表示/非表示を制御するフラグ */
    value: {
      type: Boolean,
      default: false,
    },
    /** 削除対象のカテゴリ情報 */
    item: {
      type: Object,
      required: true,
      validator: (value) => {
        return 'id' in value && 'name' in value
      },
    },
    /** カテゴリの種類（catalog または product） */
    categoryType: {
      type: String,
      required: true,
      validator: (value) => ['catalog', 'product'].includes(value),
    }
  },

  data() {
    return {
      loading: false,
      deletedItem: {
        id: null,
        name: '',
      },

      validationErrors: [],
    }
  },

  computed: {
    /**
     * カテゴリタイプの日本語表示を返す
     * @returns {string} カテゴリタイプの日本語表示
     */
    categoryTypeLabel() {
      return this.categoryType === 'catalog' ? '文書カテゴリ' : '商品カテゴリ'
    }
  },

  watch: {
    /**
     * 削除対象アイテムの監視ハンドラ
     * 親コンポーネントから新しいitem propを受け取った時に，
     * ローカルの編集用データ（deletedItem）を更新する
     *
     * @param {Object} newVal - 新しく渡されたitemの値
     * @param {number|null} newVal.id - カテゴリID
     * @param {string} newVal.name - カテゴリ名
     */
    item: {
      handler(newVal) {
        this.deletedItem = { ...newVal }
      },
      deep: true,
    },
  },

  methods: {
    /** カテゴリの削除を実行 */
    async deleteCategory() {
      try {
        this.loading = true
        this.validationErrors = []

        // APIをコールする処理をここに実装
        await this.$axios.$delete(`/api/categories/${this.deletedItem.id}`, {
          // DELETEリクエストのボディデータを送信する場合、dataオプションを使用
          data: {
            enterprise_id: 59665517,
            type: this.categoryType
          }
        })

        // 成功時は deleted イベントを発火
        this.$emit('deleted', {
          item: this.deletedItem,
          success: true,
          message: `${this.categoryTypeLabel}「${this.deletedItem.name}」を削除しました`
        })
        this.close()
      } catch (error) {
        // LaravelのValidationExceptionのエラーハンドリング
        if (error.response?.status === 422) {
          this.validationErrors = Object.values(error.response.data.errors).flat()
          return
        }
        // その他のエラー
        this.$emit('deleted', {
          item: this.deletedItem,
          success: false,
          message: '削除に失敗しました。再度お試しください。'
        })

        this.close()
      } finally {
        this.loading = false
      }
    },
    /** ダイアログを閉じる */
    close() {
      this.validationErrors = []
      this.loading = false
      this.$emit('input', false)
    },
  },
}
</script>
