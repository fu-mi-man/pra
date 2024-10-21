
<template>
  <v-dialog v-model="showPopup" max-width="400">
    <v-card>
      <v-card-title class="headline">特別なお知らせ</v-card-title>
      <v-card-text>
        これは期間限定のポップアップです。
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
    this.showPopup = this.$shouldShowPopup()
  },
  methods: {
    closePopup() {
      this.showPopup = false
    }
  }
}
</script>
