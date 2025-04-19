<!-- components/organisms/drawers/FilterDrawer.vue -->
<template>
  <v-navigation-drawer
    :value="value"
    app
    clipped
    right
    temporary
    width="300"
    @input="$emit('input', $event)"
  >
    <v-list>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="text-h6">絞り込み</v-list-item-title>
        </v-list-item-content>
        <v-list-item-action>
          <v-btn
            icon
            @click="close"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-list-item-action>
      </v-list-item>

      <v-divider></v-divider>

      <!-- 削除済みのフィルター -->
      <v-list-item>
        <v-list-item-content>
          <v-checkbox
            v-model="localFilters.deleted"
            label="削除済みを表示"
            hide-details
            @change="emitChange"
          ></v-checkbox>
        </v-list-item-content>
      </v-list-item>

      <!-- 他のフィルターオプションをここに追加 -->

      <v-divider class="my-2"></v-divider>

      <!-- フィルターリセットボタン -->
      <v-list-item>
        <v-btn
          block
          text
          color="primary"
          @click="resetFilters"
        >
          <v-icon left>mdi-refresh</v-icon>
          フィルターをリセット
        </v-btn>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  props: {
    /**
     * ドロワーの表示/非表示を制御する値
     */
    value: {
      type: Boolean,
      default: false
    },
    /**
     * 親コンポーネントから渡されるフィルター設定
     */
    filters: {
      type: Object,
      default: () => ({
        deleted: false
      })
    }
  },

  data() {
    return {
      // propsをローカルデータにコピー
      localFilters: {
        deleted: this.filters.deleted
      },
    }
  },

  watch: {
    /**
     * 親コンポーネントからのfiltersプロップが変更された時に
     * ローカルの状態を同期する
     *
     * @param {Object} newFilters - 新しいフィルター設定
     */
    filters: {
      handler(newFilters) {
        this.localFilters = { ...newFilters }
      },
      deep: true
    },
  },

  methods: {
    /**
     * フィルター変更時にイベントを発行する
     */
    emitChange() {
      this.$emit('change', { ...this.localFilters })
    },
    /**
     * フィルター設定をデフォルト値にリセットする
     * リセット後は親コンポーネントに変更を通知
     */
    resetFilters() {
      // フィルターを初期状態にリセット
      this.localFilters.deleted = false

      // イベントを発行
      this.emitChange()
    },
    /**
     * ドロワーを閉じる
     */
    close() {
      this.$emit('input', false)
    },
  }
}
</script>
