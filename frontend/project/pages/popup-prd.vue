<!-- pages/popup-prd.vue -->
<template>
  <div>
    <h1>ポップアップを表示する画面です</h1>
    <CompanyPopup />
  </div>
</template>

<script>
import CompanyPopup from '~/components/organisms/CompanyPopup.vue'

export default {
  components: {
    CompanyPopup
  }
}
</script>
