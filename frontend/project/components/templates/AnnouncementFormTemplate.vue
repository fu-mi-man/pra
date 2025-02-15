<!-- components/templates/AnnouncementFormTemplate.vue -->
<template>
  <v-container>
    <v-card max-width="800" class="mx-auto">
      <v-card-title>お知らせ作成</v-card-title>

      <v-alert v-if="errors.length > 0" type="error" dense class="mb-4">
        <ul class="mb-0 pl-4">
          <li v-for="(error, index) in errors" :key="index">
            {{ error }}
          </li>
        </ul>
      </v-alert>

      <v-card-text>
        <announcement-form
          @validation-error="onValidationError"
          @submit="onSubmit"
        />
      </v-card-text>

      <v-card-actions class="justify-end">
        <v-btn color="primary" @click="submit">保存</v-btn>
      </v-card-actions>
    </v-card>

    <v-snackbar v-model="snackbar.show" :color="snackbar.color">
      {{ snackbar.text }}
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="snackbar.show = false">
          閉じる
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import AnnouncementForm from '@/components/organisms/forms/AnnouncementForm.vue'

export default {
  components: {
    AnnouncementForm,
  },
  data() {
    return {
      errors: [],
      snackbar: {
        show: false,
        text: '',
        color: 'success',
      },
    }
  },
  methods: {
    onValidationError(errors) {
      this.errors = errors
    },
    submit() {
      this.$refs.form.submit()
    },
    async onSubmit(formData) {
      try {
        await this.$axios.post('/api/announcements', formData)
        this.snackbar = {
          show: true,
          text: 'お知らせを作成しました',
          color: 'success',
        }
        this.$router.push('/list')
      } catch (error) {
        console.error('エラー:', error.response?.data || error.message)
        this.snackbar = {
          show: true,
          text: 'お知らせの作成に失敗しました',
          color: 'error',
        }
      }
    },
  },
}
</script>
