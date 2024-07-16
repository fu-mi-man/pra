<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <v-card>
        <v-card-title class="headline">
          {{ message }}
        </v-card-title>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      message: '',
    }
  },
  async mounted() {
    try {
      const response = await this.$axios.$get('/api/hello')
      this.message = response.message
    } catch (error) {
      console.error('Error fetching data:', error)
      this.message = 'Error fetching data'
    }
  },
}
</script>
