<template>
    <v-form action="/login" method="POST">
        <v-toolbar dark color="grey darken-1">
            <v-toolbar-title>Login form</v-toolbar-title>

            <!--<v-btn href="/auth/facee" icon ><i class="fab fa-github-square fa-3x"></i></v-btn>-->

        </v-toolbar>
        <v-card-text>
            <input type="hidden" name="_token" :value="csrfToken">
            <v-text-field
                    prepend-icon="person"
                    name="email"
                    label="Login"
                    type="text"
                    v-model="dataEmail"
                    :error-messages="emailErrors"
                    @input="$v.dataEmail.$touch()"
                    @blur="$v.dataEmail.$touch()"
            >

            </v-text-field>
            <v-text-field id="password"
                          prepend-icon="lock"
                          name="password"
                          label="Password"
                          type="password"
                          v-model="password"
                          :error-messages="passwordErrors"
                          @input="$v.password.$touch()"
                          @blur="$v.password.$touch()"
            ></v-text-field>
        </v-card-text>
        <v-card-actions>
            <v-btn color="grey darken-4" class="white--text" href="/password/reset/" >Forgot your password?
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn href="/auth/facebook" icon><i class="fab fa-facebook fa-3x" style="color:#3b5998;"></i></v-btn>
            <v-btn href="/auth/github" icon ><i class="fab fa-github-square fa-3x"></i></v-btn>
        </v-card-actions>
        <v-card-actions>

            <v-btn color="grey darken-4" class="white--text" href="/register">Register</v-btn>
            <v-btn color="grey darken-4" class="white--text" type="submit"  :disabled="$v.$invalid">Login</v-btn>

        </v-card-actions>

    </v-form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, email, minLength } from 'vuelidate/lib/validators'
export default {
  name: 'LoginForm',
  mixins: [validationMixin],
  validations: {
    dataEmail: { required, email },
    password: { required, minLength: minLength(6) }
  },
  data () {
    return {
      dataEmail: this.email,
      password: ''
    }
  },
  props: [ 'email', 'csrfToken' ],
  computed: {
    emailErrors () {
      const errors = []
      if (!this.$v.dataEmail.$dirty) return errors
      !this.$v.dataEmail.email && errors.push('El camp email ha de ser un email vàlid.')
      !this.$v.dataEmail.required && errors.push('El camp email és obligatori.')
      return errors
    },
    passwordErrors () {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      !this.$v.password.minLength && errors.push('El camp password ha de tenir una mida mínima de 6 caràcters.')
      !this.$v.password.required && errors.push('El camp password és obligatori.')
      return errors
    }
  }
}
</script>
