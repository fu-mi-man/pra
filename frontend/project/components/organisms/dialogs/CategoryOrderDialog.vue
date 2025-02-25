<!-- components/organisms/dialogs/CategoryOrderDialog.vue -->
<template>
  <v-dialog
    :value="value"
    max-width="600px"
    persistent
    @input="$emit('input', $event)"
  >
    <v-card>
      <!-- ローディングオーバーレイ -->
      <loading-overlay :value="loading" />

      <v-card-title class="text-h5">
        {{ dialogTitle }}
      </v-card-title>

      <v-card-text>
        <!-- バリデーションエラーメッセージ -->
        <validation-error-alert :errors="validationErrors" />

        <draggable
          v-model="localCategories"
          tag="v-list"
          handle=".handle"
          :disabled="loading"
          @start="drag = true"
          @end="drag = false"
        >
          <v-list-item
            v-for="(category, index) in localCategories"
            :key="category.id"
            :class="{ 'grey lighten-4': drag }"
          >
            <v-list-item-icon class="handle mr-2">
              <v-icon>mdi-drag</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>
                {{ category.name }}
              </v-list-item-title>
              <v-list-item-subtitle class="text--secondary">
                現在の表示順: {{ index + 1 }}
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </draggable>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          :disabled="loading"
          text
          @click="close"
        >
          キャンセル
        </v-btn>
        <v-btn
          color="primary"
          :disabled="loading"
          :loading="loading"
          @click="handleSave"
        >
          保存
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
// `yarn add vuedraggable`
// `yarn add vue2-datepicker vuedraggable` 依存関係でこちらのコマンドを実行
import draggable from 'vuedraggable'
import ValidationErrorAlert from '@/components/molecules/alerts/ValidationErrorAlert.vue'
import LoadingOverlay from '@/components/molecules/overlays/LoadingOverlay.vue'

export default {
  name: 'CategoryOrderDialog',

  components: {
    draggable,
    ValidationErrorAlert,
    LoadingOverlay,
  },

  props: {
     /** ダイアログの表示状態を制御する値 */
    value: {
       type: Boolean,
       required: true
    },
    /** 表示および並び替え対象のカテゴリ一覧 */
    categories: {
      type: Array,
      required: true
    },
    /** カテゴリのタイプ（'catalog'または'product'） */
    categoryType: {
      type: String,
      required: true,
      validator: value => ['catalog', 'product'].includes(value)
    },
  },

  data() {
    return {
      loading: false,
      drag: false,
      // ローカルの配列を作成（キャンセル時の復元用）
      localCategories: [],

      validationErrors: [],
    }
  },

  computed: {
    /** ダイアログのタイトルを動的に生成する */
    dialogTitle() {
      return `${this.categoryType === 'catalog' ? '文書カテゴリ' : '商品カテゴリ'}の並び順を変更`
    },
  },

  watch: {
    /**
     * ダイアログの表示状態を監視する
     * @param {boolean} newVal - ダイアログの新しい表示状態の値
     */
    value(newVal) {
      if (newVal) {
        // ダイアログが開かれたときに配列をコピー
        this.localCategories = [...this.categories]
      }
    }
  },

  methods: {
    async handleSave() {
      this.loading = true

      try {

        // 並び順を更新したカテゴリの配列を作成
        const updatedCategories = this.localCategories.map((category, index) => ({
          id: category.id,
          display_order: index + 1
        }))
        // APIリクエスト
        await this.$axios.$put('/api/categories/order', {
          type: this.categoryType,
          enterprise_id: 59665517,
          categories: updatedCategories
        })

        // 親コンポーネントに成功を通知
        this.$emit('completed', {
          success: true,
          categories: this.localCategories,
          message: '並び順を更新しました'
        })

        // ダイアログを閉じる
        this.$emit('input', false)
      } catch (error) {
        if (error.response) {
          console.error('Error details:', error.response.data);
        }
        if (error.response?.status === 422) {
          console.error('Failed to update category order:', error)
          this.validationErrors = Object.values(error.response.data.errors).flat()
          return
        }
        // this.$emit('deleted', {
        //   item: this.deletedItem,
        //   success: false,
        //   message: '並び順の更新に失敗しました。再度お試しください。'
        // })
      } finally {
        this.loading = false
      }
    },

    /**
     * ダイアログを閉じ，エラー状態をクリアする
     */
    close() {
      this.validationErrors = []
      this.loading = false
      this.$emit('input', false)
    },
  }
}
</script>

<style scoped>
.handle {
  cursor: move;
}
</style>
