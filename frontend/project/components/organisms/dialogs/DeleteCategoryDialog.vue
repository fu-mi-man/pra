<template>
  <v-dialog
    :value="value"
    max-width="500px"
    persistent
    @input="$emit('input', $event)"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">
          {{ categoryTypeLabel }} の削除確認
        </span>
      </v-card-title>

      <v-card-text>
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
          :loading="loading"
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
    // async deleteCategory() {
    deleteCategory() {
      try {
        this.loading = true
        this.error = null
        // APIをコールする処理をここに実装
        // await this.deleteCategoryAPI(this.item.id);

        // 成功時は deleted イベントを発火
        this.$emit('deleted', {
          item: this.item,
          success: true,
          message: `${this.categoryTypeLabel}「${this.item.name}」を削除しました`
        })
        this.close()
      } catch (error) {
        // エラー時は deleted イベントでエラー情報を渡す
        this.$emit('deleted', {
          item: this.item,
          success: false,
          message: '削除に失敗しました。再度お試しください。'
        })
      } finally {
        this.loading = false
      }
    },
    /** ダイアログを閉じる */
    close() {
      this.$emit('input', false)
      this.error = null
    },
  },
}
</script>
