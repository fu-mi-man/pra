<!-- components/organisms/dialogs/CategoryFormDialog.vue -->
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

      <v-card-title>
        <span class="text-h5">
          {{ dialogTitle }}
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
          {{ modeActionLabel }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import ValidatedTextField from '@/components/atoms/inputs/base/ValidatedTextField.vue'
import LoadingOverlay from '@/components/molecules/overlays/LoadingOverlay.vue'

/**
 * カテゴリ編集用ダイアログコンポーネント
 * カテゴリの新規作成・編集に使用される
 */
export default {
  components: {
    ValidatedTextField,
    LoadingOverlay,
  },
  props: {
    /** ダイアログの表示/非表示を制御するフラグ */
    value: {
      type: Boolean,
      default: false,
    },
    /** モード（'create': 新規作成, 'edit': 編集） */
    mode: {
      type: String,
      required: true,
      validator: (value) => ['create', 'edit'].includes(value)
    },
    /** 編集対象のカテゴリ情報 */
    item: {
      type: Object,
      required: true,
      // コンソールにエラーが出る程度であってもなくてもどっちでもいい
      validator: (value) => {
        return 'id' in value && 'name' in value
      },
    },
    /** カテゴリの種類（catalog または product） */
    categoryType: {
      type: String,
      required: true,
      // コンソールにエラーが出る程度であってもなくてもどっちでもいい
      validator: (value) => ['catalog', 'product'].includes(value),
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
    /** 現在のモードに応じたダイアログのタイトルを返す */
    dialogTitle() {
      return this.mode === 'create' ? 'カテゴリを作成' : 'カテゴリを編集'
    },
    /** カテゴリの種類に応じたラベルを返す */
    categoryTypeLabel() {
      return this.categoryType === 'catalog' ? '文書カテゴリ' : '商品カテゴリ'
    },
    /** 現在のモードに応じた保存ボタンのテキストを返す */
    modeActionLabel() {
      return this.mode === 'create' ? '作成' : '更新'
    }
  },
  watch: {
    /**
     * 編集対象アイテムの監視ハンドラ
     * 親コンポーネントから新しいitem propを受け取った時に，
     * ローカルの編集用データ（editedItem）を更新する
     *
     * @param {Object} newVal - 新しく渡されたitemの値
     * @param {number|null} newVal.id - カテゴリID
     * @param {string} newVal.name - カテゴリ名
     */
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
     * モードに応じてPOSTまたはPUT APIを呼び出す
     */
    async save() {
      if (!this.isFormValid) return

      try {
        this.loading = true
        this.validationErrors = []

        const payload = {
          enterprise_id: 59665517,
          name: this.editedItem.name,
          type: this.categoryType
        }

        const response = this.mode === 'create'
          ? await this.$axios.$post('/api/categories', payload)
          : await this.$axios.$put(`/api/categories/${this.editedItem.id}`, payload)

        // テスト用コード（2秒スリープ・仮API）
        // await new Promise(resolve => setTimeout(resolve, 2000))

        this.$emit('completed', {
          item: response,
          mode: this.mode,
          success: true,
          message: `${this.categoryTypeLabel}を${this.modeActionLabel}しました`
        })
        this.close()
      } catch (error) {
        if (error.response?.status === 422) {
          // console.log(error.response.data)
          // {
          //     "message": "選択された 出展者ID は無効です。 (and 2 more errors)",
          //     "errors": {
          //         "enterprise_id": [
          //             "選択された 出展者ID は無効です。",
          //             "出展者ID は必須です。" // `bail`をつけない場合
          //         ],
          //         "name": [
          //             "カテゴリ名 は必須です。"
          //         ]
          //     }
          // }
          // console.log(Object.values(error.response.data.errors))
          // [
          //     [
          //         "選択された 出展者ID は無効です。",
          //         "出展者ID は必須です。"
          //     ],
          //     [
          //         "カテゴリ名 は必須です。"
          //     ]
          // ]
          // PHP側で配列化されているエラーメッセージをそのままjson化しているので`[]`つきで表示されてしまう問題を解決
          this.validationErrors = Object.values(error.response.data.errors).flat()
        } else {
          this.$emit('completed', {
            item: this.editedItem,
            success: false,
            message: `${this.modeActionLabel}に失敗しました。再度お試しください。`
          })
        }
      } finally {
        this.loading = false
      }
    },
    /**
     * ダイアログを閉じ，エラー状態をクリアする
     * 編集データは親コンポーネントからの新しいitemの受け取り時にwatcherで自動的に更新される
     */
    close() {
      this.errorMessages = []
      this.validationErrors = []
      this.loading = false
      this.$emit('input', false) // ダイアログを閉じる
    },
  },
}
</script>
