<template>
    <v-switch
            v-model="dataValue"
            :label="dataValue ? activeText : unactiveText"
            :loading="loading"
            class="mt-0"
    >
    </v-switch>
</template>

<script>
export default {
  name: 'toggle',
  data () {
    return {
      dataValue: this.value,
      dataTask: this.task,
      // oldValue: this.value.completed,
      loading: false
    }
  },
  props: {
    activeText: {
      type: String,
      default: 'Active'
    },
    unactiveText: {
      type: String,
      default: 'Unactive'
    },
    uri: {
      type: String,
      required: true
    },
    value: {
      type: Boolean,
      required: true
    },
    task: {
      type: Object,
      required: true
    }
  },
  watch: {
    dataValue (dataValue) {
      if (dataValue !== this.dataTask.completed) {
        if (dataValue) this.completeTask()
        else this.uncompleteTask()
      }
    },
    task (task) {
      this.dataTask = task
    },
    value (value) {
      this.dataValue = value
    }
  },
  methods: {
    completeTask () {
      this.loading = true
      window.axios.post(this.uri + '/' + this.task.id).then(() => {
        this.loading = false
        this.$emit('change')
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    },
    uncompleteTask () {
      this.loading = true
      window.axios.delete(this.uri + '/' + this.task.id).then(() => {
        this.loading = false
        this.$emit('change')
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    }
  }
}
</script>
