<!-- components/organisms/dialogs/CategoryOrderDialog.vue -->
<template>
  <v-dialog v-model="dialog" max-width="600px" persistent>
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
          text
          @click="handleCancel"
          :disabled="loading"
        >
          キャンセル
        </v-btn>
        <v-btn
          color="primary"
          @click="handleSave"
          :loading="loading"
          :disabled="loading"
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
    value: {
      type: Boolean,
      required: true
    },
    categories: {
      type: Array,
      required: true
    },
    categoryType: {
      type: String,
      required: true,
      validator: value => ['catalog', 'product'].includes(value)
    },
    enterpriseId: {
      type: Number,
      required: true
    }
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
    dialog: {
      get() {
        return this.value
      },
      set(value) {
        this.$emit('input', value)
      }
    },
    /** ダイアログのタイトルを動的に生成する */
    dialogTitle() {
      return `${this.categoryType === 'catalog' ? '文書カテゴリ' : '商品カテゴリ'}の並び順を変更`
    },
  },

  watch: {
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
        this.dialog = false
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

    handleCancel() {
      // ダイアログを閉じる
      this.dialog = false
      // 親コンポーネントにキャンセルを通知
      this.$emit('completed', {
        success: false,
        categories: this.categories, // 元の配列を返す
        message: null
      })
    }
  }
}
</script>

<style scoped>
.handle {
  cursor: move;
}
</style>
