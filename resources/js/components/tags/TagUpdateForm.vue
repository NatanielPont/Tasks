<template>
    <v-form>
        <v-text-field v-model="name" label="Nom" hint="El nom de l'etiqueta..." placeholder="Nom de l'etiqueta"></v-text-field>
        <!--<v-icon x-large :color="color">memory</v-icon>-->
        <input type="color" v-model="color" label="Color" style="width: 50px; height: 50px;">
        <!--<v-textarea v-model="tagBeingEdited.description" label="Descripción" hint="Descripció"></v-textarea>-->
        <!--<v-text-field v-model="color" label="Color" hint="El color de l'etiqueta..." placeholder="Color de l'etiqueta"></v-text-field>-->
        <v-textarea v-model="description" label="Descripció" hint="bla bla bla..."></v-textarea>

        <div class="text-xs-center">
            <v-btn @click="$emit('close')">
                <v-icon class="mr-1">exit_to_app</v-icon>
                Cancel·lar
            </v-btn>
            <v-btn color="success" @click="update" :disabled="working" :loading="working">
                <v-icon class="mr-1" >save</v-icon>
                Actualitzar
            </v-btn>
        </div>
    </v-form>
</template>

<script>
export default {
  name: 'TaskUpdateForm',
  data () {
    return {
      name: this.tag.name,
      color: this.tag.color,
      description: this.tag.description,
      working: false
    }
  },
  props: {
    tag: {
      type: Object,
      required: true
    }
  },
  watch: {
    tag (tag) {
      this.name = tag.name
      this.color = tag.color
      this.description = tag.description
    }
  },
  methods: {
    update () {
      this.working = true
      this.tag.color = this.color
      if (this.name.length > 0) { this.tag.name = this.name }
      if (this.description.length > 0) { this.tag.description = this.description }
      // window.axios.put(this.uri + '/' + this.tag.id, this.tag).then(() => {
      //   // this.editTag(this.tagBeingEdited)
      //   // this.$snackbar.showMessage('Se ha editado correctamente la etiqueta')
      //   this.refresh()
      //   this.editing = false
      //   this.editDialog = false
      // }).catch((error) => {
      //   this.$snackbar.showError(error)
      //   this.editing = false
      //   this.editDialog = false
      // })

      window.axios.put('/api/v1/tags/' + this.tag.id, this.tag).then((response) => {
        this.$emit('updated', response.data)
        console.log(response.data)
        this.$emit('close')
        this.working = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.working = false
      })
    }
  }
}
</script>
