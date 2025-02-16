<template>
  <v-dialog
    :value="value"
    max-width="500px"
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
          {{ categoryTypeLabel }} の削除確認
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
/**
 * カテゴリ削除用ダイアログコンポーネント
 * カテゴリの削除前の確認に使用される
 */
export default {
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
    },
    /** カテゴリタイプ */
    categoryType: {
      type: String,
      required: true,
    }
  },

  data() {
    return {
      loading: false,
      error: null,
      validationErrors: [],
    }
  },

  computed: {
    /**
     * カテゴリタイプの日本語表示を返す
     * @returns {string} カテゴリタイプの日本語表示
     */
    categoryTypeLabel() {
      return this.categoryType === 'document' ? '文書カテゴリ' : '商品カテゴリ'
    }
  },

  methods: {
    /** カテゴリの削除を実行 */
    async deleteCategory() {
    // deleteCategory() {
      try {
        this.loading = true
        this.error = null
        this.validationErrors = []

        // APIをコールする処理をここに実装
        // await this.deleteCategoryAPI(this.item.id);
        // テスト用コード（2秒スリープ・仮API）
        await new Promise(resolve => setTimeout(resolve, 2000))
        // テスト用コード（バリデーションエラー）
        // const mockError = new Error('Validation Error')
        // mockError.response = {
        //   status: 422,
        //   data: {
        //     errors: {
        //       category: ['このカテゴリは使用中のため削除できません'],
        //       items: ['関連する商品が存在するため削除できません']
        //     }
        //   }
        // }
        // throw mockError

        // 成功時は deleted イベントを発火
        this.$emit('deleted', {
          item: this.item,
          success: true,
          message: `${this.categoryTypeLabel}「${this.item.name}」を削除しました`
        })
        this.close()
      } catch (error) {
        // LaravelのValidationExceptionのエラーハンドリング
        if (error.response?.status === 422) {
          // バリデーションエラーメッセージの取得
          const errors = error.response.data.errors
          this.validationErrors = Object.values(errors).flat()
        } else {
          // その他のエラー
          this.$emit('deleted', {
            item: this.item,
            success: false,
            message: '削除に失敗しました。再度お試しください。'
          })
        }
      } finally {
        this.loading = false
      }
    },
    /** ダイアログを閉じる */
    close() {
      this.error = null
      this.validationErrors = []
      this.$emit('input', false)
    },
  },
}
</script>
