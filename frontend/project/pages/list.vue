<!-- pages/index.vue -->
<template>
  <div>
    <h1>ディレクトリナビゲーター</h1>
    <ul>
      <li v-for="(item, index) in directoryStructure" :key="index">
        <nuxt-link :to="item.path">
          {{ item.name }}
        </nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      directoryStructure: [
        { name: 'ホーム', path: '/' },
        { name: '見積もり印刷', path: '/print' },
        { name: '印刷・プレビュー', path: '/preview' },
        { name: 'グループセレクション', path: '/group-selection' },
        { name: '価格ダイアログ', path: '/price' },
        { name: '価格画面', path: '/priceScreen' },
        { name: 'プロファイル', path: '/inspire' },
        { name: 'ダイアログ', path: '/dialog' },
        { name: 'ポップアップ', path: '/popup' },
        { name: 'ポップアップPRD', path: '/popup-prd' },
        { name: 'ポップアップURL', path: '/popup-url' },
        { name: 'S3', path: '/s3' },
        { name: 'csvアップロード', path: '/upload' },
        { name: 'csvダウンロード', path: '/csvDownload' },
        { name: 'csvアップロード2', path: '/csvUpload' },
        { name: 'csv&imageアップロード', path: '/uploadpage' },
        { name: 'zipアップロード', path: '/zipuploadpage' },
      ]
    }
  }
}
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
li {
  margin-bottom: 10px;
}
a {
  text-decoration: none;
  color: #3498db;
  font-size: 18px;
}
a:hover {
  text-decoration: underline;
}
</style>
