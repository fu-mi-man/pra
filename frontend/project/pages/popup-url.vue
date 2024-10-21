<template>
  <v-dialog v-model="showPopup" max-width="400">
    <v-card>
      <v-card-title class="headline">製品ページ特別お知らせ</v-card-title>
      <v-card-text>
        これは製品ページ1への訪問者向けの期間限定ポップアップです。
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="closePopup">
          閉じる
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data() {
    return {
      showPopup: false
    }
  },
  mounted() {
    this.checkRouteAndShowPopup()
  },
  methods: {
    checkRouteAndShowPopup() {
      const isAmPage = this.$route.path === '/am/product/'
      const isPageOne = this.$route.query.page === '1'
      if (isAmPage && isPageOne) {
        this.showPopup = this.$shouldShowPopup()
      } else {
        this.showPopup = false
      }
    },
    closePopup() {
      this.showPopup = false
    }
  },
  watch: {
    '$route'() {
      this.checkRouteAndShowPopup()
    }
  }
}
</script>
