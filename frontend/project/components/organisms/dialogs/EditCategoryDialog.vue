<template>
  <v-dialog
    :value="value"
    max-width="600px"
    persistent
    @input="$emit('input', $event)"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">
          カテゴリを編集
        </span>
      </v-card-title>

      <v-card-text>
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
          :disabled="!isFormValid"
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
    },
    /** カテゴリの種類（document または product） */
    categoryType: {
      type: String,
      required: true,
    },
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
  data() {
    return {
      loading: false,     // ローディング
      isFormValid: false, // フォームの検証状態
      editedItem: {
        id: null,
        name: '',
      },
      errorMessages: [],  // エラーメッセージの配列
    }
  },
  methods: {
    /**
     * フォームの保存処理
     * バリデーションが成功した場合のみ実行される
     */
    // async save() {
    save() {
      if (!this.isFormValid) return

      try {
        this.loading = true
        // APIをコールする処理をここに実装
        // await this.updateCategoryAPI(this.editedItem);

        this.$emit('saved', this.editedItem)
        this.close()
      } catch (error) {
        this.errorMessages = ['保存中にエラーが発生しました']
      } finally {
        this.loading = false
      }
    },
    /**
     * ダイアログを閉じる
     * フォームの状態をリセットし，編集データをクリアする
     */
    close() {
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
