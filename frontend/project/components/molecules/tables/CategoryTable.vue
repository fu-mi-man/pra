<!-- components/molecules/tables/CategoryTable.vue -->
<template>
  <v-data-table
    :headers="headers"
    :items="items"
    :loading="loading"
    hide-default-footer
    no-data-text="表示するカテゴリがありません"
  >
    <!-- 編集アイコン -->
    <template #[`item.edit`]="{ item }">
      <v-btn icon small @click="$emit('edit', item)">
        <v-icon small>mdi-pencil</v-icon>
      </v-btn>
    </template>

    <!-- 削除アイコン -->
    <template #[`item.delete`]="{ item }">
      <v-btn icon small @click="$emit('delete', item)">
        <v-icon small>mdi-delete</v-icon>
      </v-btn>
    </template>
  </v-data-table>
</template>

<script>
export default {
  name: 'CategoryTable',
  props: {
    /** テーブルのヘッダー */
    headers: {
      type: Array,
      required: true
    },
    /** 表示するデータ */
    items: {
      type: Array,
      required: true
    },
    /** ローディング状態 */
    loading: {
      type: Boolean,
      default: false
    },
  },
}
</script>
