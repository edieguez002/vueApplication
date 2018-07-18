<template>
  <div class="container">
    <div class="row">

        <a class="col s4 offset-s2 z-depth-4 btn" @click="toggleStatus(true)">Sign in</a>
        <a class="col s4 z-depth-4 btn" @click="toggleStatus(false)">Sign up</a>

        <template v-if="SigningIn">
            <div id="formAccess" class="row col s4 offset-s4">
                <form>
                    <div class="row">
                        <div class="input-field">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="username" type="text" class="validate" v-model="user.username" />
                            <label for="username">Username or email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <i class="material-icons prefix">lock</i>
                            <input id="password" type="password" class="validate" v-model="user.password" />
                            <label for="password">Password</label>
                        </div>
                    </div>
                </form>
                <button class="waves-effect waves-light z-depth-5 btn" type="submit" v-on:click="signInUser">Sign in</button>
            </div>
        </template>

        <template v-else>
            <div class="row">
                <form id="formAccess" class="col s8 offset-s2">
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">contacts</i>
                            <input id="firstname" type="text" class="validate" v-model="user.firstname" />
                            <label for="firstname">First name</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="lastname" type="text" class="validate" v-model="user.lastname" />
                            <label for="lastname">Last name</label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="username" type="text" class="validate" v-model="user.username" />
                            <label for="username">Username</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <i class="material-icons prefix">contact_mail</i>
                            <input id="email" type="text" class="validate" v-model="user.email" />
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <i class="material-icons prefix">lock</i>
                            <input id="password" type="password" class="validate" v-model="user.password" />
                            <label for="password">Password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field">
                            <i class="material-icons prefix">lock</i>
                            <input id="password2" type="password" class="validate" v-model="user.password2" />
                            <label for="password2">Repeat password</label>
                        </div>
                    </div>
                </form>
                <button class="waves-effect waves-light z-depth-5 col s4 offset-s4 btn" type="submit" v-on:click="signUpUser">Sign up</button>
            </div>
        </template>
    </div>
  </div>
</template>

<script>
import Axios from 'axios';
import userInfo from '@/store/userInfo';
import router from '@/router/index'

export default {
  name: 'Access',
  data () {
    return {
      SigningIn: true,
      user: {firstname:'', lastname:'', username:'', email:'', password:'', password2:''}
    }
  },
    methods: {
        toggleStatus: function(a) {
            if (a) {
                this.SigningIn = true;
                this.user.firstname = "";
                this.user.lastname = "";
                this.user.username = "";
                this.user.email = "";
                this.user.password = "";
                this.user.password2 = "";
            }else {
                this.SigningIn = false;
                this.user.firstname = "";
                this.user.lastname = "";
                this.user.username = "";
                this.user.email = "";
                this.user.password = "";
                this.user.password2 = "";
            }
        },
        signInUser: function() {
            var regexp = new RegExp("@");
            if (this.user.username != "" && this.user.password != ""){
                if (regexp.test(this.user.username) == true) {
                    console.log(this.user.username);
                    this.user.email = this.user.username;
                    this.user.username = "";
                }
                var params = this.toFormData(this.user);
                console.log(this.user);
                Axios.post('http://localhost:80/APICRUD/Auth/signIn.php', params)
                    .then(function (response) {
                        if (response.data == "EMPTY") {
                            alert("Username and/or password are incorrect. Try again");
                        } else if (response.data == "ERROR") {
                            alert("An error ocurred during the transaction. Try again.");
                        }
                        else if (response.data == null) {
                            alert("Internal error. May have problems with the connection.");
                        } else {
                            console.log(response.data);
                            //To share state with other components. State manager: Vuex
                            userInfo.dispatch('signingUserInfo', response.data);
                            console.log("Welcome back, " + response.data);
                            $('.modal').modal('close');
                            Axios.post('http://localhost:80/APICRUD/Projects/getUserProjects.php', params)
                                .then(function (response){
                                    var proInfo = response.data.split("/");
                                    userInfo.dispatch('gettingUserProjects', proInfo);
                                    window.location.reload();
                                })
                                .catch(function (error) {
                                    console.log(error);
                            });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            }else {
                alert('You must fill the required fields.');
            }
            this.clearForm();
        },
        signUpUser: function() {
            var regexpEmail = new RegExp("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}");
            var regexpPassword = new RegExp("[a-zA-Z0-9]{8,}");
            if (this.user.firstname != "" && this.user.lastname != "" && this.user.username != "" && this.user.email != "" && this.user.password != "" && this.user.password2 != "") {
                if(regexpEmail.test(this.user.email) && this.user.password == this.user.password2 && regexpPassword.test(this.user.password)) {
                    var params = this.toFormData(this.user);
                    var result = "";
                    var self = this;
                    Axios.post('http://localhost:80/APICRUD/Auth/signUp.php', params)
                    .then(function(response) {
                        result = response.data;
                        if (result == "ERROR") {
                            alert("There was an error signing up the user. Try again.");
                        } else if (result == "USERNAMEACTIVE") {
                            alert("This username already exists. Choose another one.");
                            self.user.username = "";
                        } else if (result == "EMAILACTIVE") {
                            alert("This email already exists. Choose another one.");
                            self.user.email = "";
                        } else {
                            //To share state with other components. State manager: Vuex
                            userInfo.dispatch('signingUserInfo', result);
                            console.log("User added! \n");
                            $('.modal').modal('close');
                            window.location.reload();
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                } else if (!regexpEmail.test(this.user.email)) {
                    alert("Invalid email address.");
                    this.user.email = "";
                } else if (!regexpPassword.test(this.user.password)) {
                    alert("The password must contain at least 8 alphanumeric characters.");
                    this.user.password = "";
                    this.user.password2 = "";
                } else {
                    alert("The passwords need to be the same.");
                    this.user.password = "";
                    this.user.password2 = "";
                }
            } else {
                alert("You need to fill all the fields.");
            }
        },
        toFormData: function(e) {
            var form_data = new FormData();
            for (var key in e){
                form_data.append(key, e[key]);
            }
            return form_data;
        },
        clearForm: function() {
            this.user.firstname = "";
            this.user.lastname = "";
            this.user.username = "";
            this.user.email = "";
            this.user.password = "";
            this.user.password2 = "";
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#formAccess {
    margin-top: 10px;
}
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
::-moz-placeholder {
    color: #8a8a5d;
    font-style: italic;
}
::placeholder {
    color: #8a8a5d;
    font-style: italic;
}
</style>
