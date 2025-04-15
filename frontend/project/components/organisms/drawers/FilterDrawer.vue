<!-- components/organisms/drawers/FilterDrawer.vue -->
<template>
  <v-navigation-drawer
    v-model="isOpen"
    app
    clipped
    right
    temporary
    width="300"
  >
    <v-list>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="text-h6">フィルター</v-list-item-title>
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
    value: {
      type: Boolean,
      default: false
    },
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
      }
    }
  },

  watch: {
    // propsが変更されたらローカルデータを更新
    filters: {
      handler(newFilters) {
        this.localFilters = { ...newFilters }
      },
      deep: true
    }
  },

  computed: {
    isOpen: {
      get() {
        return this.value
      },
      set(value) {
        this.$emit('input', value)
      }
    }
  },

  methods: {
    /**
     * ドロワーを閉じる
     */
    close() {
      this.isOpen = false
    },

    /**
     * フィルター変更時にイベントを発行する
     */
    emitChange() {
      this.$emit('change', { ...this.localFilters })
    },

    /**
     * フィルターをリセットする
     */
    resetFilters() {
      // フィルターを初期状態にリセット
      this.localFilters.deleted = false

      // イベントを発行
      this.emitChange()
    }
  }
}
</script>
