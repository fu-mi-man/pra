<!-- components/organisms/dialogs/DeleteRequestDialog.vue -->
<template>
  <v-dialog
    :value="value"
    max-width="500px"
    persistent
    @input="$emit('input', $event)"
  >
    <v-card>
      <v-card-title>
        <v-icon>
          mdi-alert-circle
        </v-icon>
      </v-card-title>

      <v-card-text>
        <!-- バリデーションエラーメッセージ -->
        <validation-error-alert :errors="validationErrors" />

        <p class="mb-0">
          削除してもよろしいですか？
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
          @click="deleteRequest"
        >
          削除
        </v-btn>
      </v-card-actions>
    </v-card>

    <!-- ローディングオーバーレイ -->
    <loading-overlay :value="loading" />
  </v-dialog>
</template>

<script>
import ValidationErrorAlert from '@/components/molecules/alerts/ValidationErrorAlert.vue'
import LoadingOverlay from '@/components/molecules/overlays/LoadingOverlay.vue'

/**
 * リクエスト削除用ダイアログコンポーネント
 * リクエストの削除前の確認に使用される
 */
export default {
  components: {
    ValidationErrorAlert,
    LoadingOverlay,
  },

  props: {
    /** ダイアログの表示/非表示を制御する値 */
    value: {
      type: Boolean,
      default: false
    },
    /** 削除対象のリクエスト情報 */
    selectedRequest: {
      type: [Object, null], // null値も許容
      default: () => ({}),  // デフォルト値として空オブジェクトを返す
      // validator: (value) => {
      //   return value && 'id' in value
      // }
    },
  },

  data() {
    return {
      loading: false,
      deletedItem: {
        id: null,
        name: ''
      },
      validationErrors: []
    }
  },

  watch: {
    /**
     * 削除対象アイテムの監視ハンドラ
     * 親コンポーネントから新しいitem propを受け取った時に，
     * ローカルの編集用データ（deletedItem）を更新する
     *
     * @param {Object} newVal - 新しく渡されたitemの値
     * @param {number|null} newVal.id - 商品ID
     * @param {string} newVal.name - 商品名
     */
    selectedRequest: {
      handler(newVal) {
        this.deletedItem = { ...newVal }
      },
      deep: true
    }
  },

  methods: {
    /** リクエストの削除を実行 */
    async deleteRequest() {
      try {
        this.loading = true
        this.validationErrors = []

        await this.$axios.$delete(`/api/enterprises/${this.deletedItem.id}`, {
          // DELETEリクエストのボディデータを送信する場合、dataオプションを使用
          data: {
            enterprise_id: 59665517,
          }
        })

        // 成功時は deleted イベントを発火
        this.$emit('deleted', {
          success: true,
          message: `リクエストを削除しました。`
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
    }
  }
}
</script>
