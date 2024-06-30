<template>
  <v-app>
    <!-- ヘッダー（v-app-bar）やナビゲーション（v-navigation-drawer）などの共通要素 -->
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <!-- フッター（v-footer）などの共通要素 -->
  </v-app>
</template>

<script>
export default {
  name: 'DefaultLayout'
}
</script>
