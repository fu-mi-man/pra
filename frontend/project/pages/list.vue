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
        { name: 'グループセレクション', path: '/group-selection' },
        { name: '価格', path: '/price' },
        { name: 'プロファイル', path: '/inspire' },
        { name: 'ダイアログ', path: '/dialog' },
        { name: 'ポップアップ', path: '/popup' },
        { name: 'ポップアップPRD', path: '/popup-prd' },
        { name: 'ポップアップURL', path: '/popup-url' },
        { name: 'S3', path: '/s3' },
        { name: 'csvアップロード', path: '/upload' },
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
