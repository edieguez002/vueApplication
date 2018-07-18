<template>
    <div id="profileColumns"class="row">

            <!-- Flat action button -->
            <div class="fixed-action-btn click-to-toggle hide-on-large-only">
                <a class="btn-floating btn-large teal">
                  <i class="large material-icons">settings</i>
                </a>
                <ul>
                    <li>
                        <a class="btn-floating lighten-1"><i class="material-icons" v-on:click="editProfile">edit</i></a>
                        <a class="btn-floating mobile-fab-tip" v-on:click="editProfile">Edit profile</a>
                    </li>
                    <li>
                        <a class="btn-floating red lighten-1"><i class="material-icons">delete_forever</i></a>
                        <a class="btn-floating mobile-fab-tip red lighten-1">Delete user</a>
                    </li>
                </ul>
            </div>

            <br />
            <div id="profileForm">
                <!--User profile edition form-->
                <div class="row">
                    <form class="col s10 push-s1">
                        <div class="row">
                            <div class="input-field col s5">
                                  <input disabled id="first_name" type="text" class="validate" v-model="newUser.newFirstname">
                                  <label class="active" for="first_name">Name</label>
                            </div>
                            <div class="input-field col s5">
                                  <input disabled id="last_name" type="text" class="validate" v-model="newUser.newLastname">
                                  <label class="active" for="last_name">Last Name</label>
                            </div>
                            <div class="input-field col s5">
                                  <input disabled id="username" type="text" class="validate" v-model="newUser.newUsername">
                                  <label class="active" for="username">Username</label>
                            </div>
                            <div class="input-field col s5">
                                  <input disabled id="email" type="text" class="validate" v-model="newUser.newEmail">
                                  <label class="active" for="email">Email</label>
                            </div>
                            <div class="input-field col s10">
                                <input disabled id="password" type="password" class="validate" v-model="newPassword">
                                <label class="active" for="password">New password</label>
                            </div>
                        </div>
                    </form>
                    <a id="editButton" class="waves-effect waves-light z-depth-3 btn hide-on-med-and-down" v-on:click="editProfile">Edit</a>
                    <br />
                    <br />
                    <!--<a class="waves-effect waves-light z-depth-3 btn red lighten-1 hide-on-med-and-down">Delete user</a>-->
                    <br />
                </div>
            </div>
    </div>
</template>
<script>
import Axios from 'axios'
import userInfo from '@/store/userInfo.js'

export default {
    name: "Profile",
    data() {
        return {
            user: {
                firstname: userInfo.getters.getFirstname,
                lastname: userInfo.getters.getLastname,
                username: userInfo.getters.getUsername,
                email: userInfo.getters.getEmail,
            },
            newUser: {
                newFirstname: "",
                newLastname: "",
                newUsername: "",
                newEmail: "",
            },
            newPassword: ""
        }
    },
    mounted: function() {
        console.log(this.newUser);
        this.fillFields();
        console.log(this.newUser);
    },
    methods: {
        fillFields: function() {
            document.getElementById("first_name").value = this.user.firstname;
            document.getElementById("last_name").value = this.user.lastname;
            document.getElementById("username").value = this.user.username;
            document.getElementById("email").value = this.user.email;
            document.getElementById("password").value = "";
        },
        editProfile: function() {
            if (document.getElementById("editButton").text == "Edit") {
                this.toggleInputFields();
            } else if (this.newUser.newFirstname == "" && this.newUser.newLastname == "" && this.newUser.newUsername == "" && this.newUser.newEmail == "" && this.newPassword == "") {
                this.fillFields();
                this.toggleInputFields();
            } else {
                var saveFirstname = (this.newUser.newFirstname != "") ? this.newUser.newFirstname : this.user.firstname;
                var saveLastname = (this.newUser.newLastname != "") ? this.newUser.newLastname : this.user.lastname;
                var saveUsername = (this.newUser.newUsername != "") ? this.newUser.newUsername : this.user.username;
                var saveEmail = (this.newUser.newEmail != "") ? this.newUser.newEmail : this.user.email;
                var saveUser = { oldUsername: this.user.username, oldEmail: this.user.email, firstname: saveFirstname, lastname: saveLastname, username: saveUsername, email: saveEmail, password: this.newPassword };
                var params = this.toFormData(saveUser);
                var self = this;
                Axios.post('http://localhost:80/APICRUD/Auth/setUpProfile.php', params)
                .then(function (response) {
                    console.log(response.data);
                    if (response.data == "EMAILUSERNAMEACTIVE") {
                        alert("The username or email you want it's in use already. Try again.");
                        this.fillFields();
                    } else if (response.data == "ERROR") {
                        alert("There was an error updating your profile. Try again later.");
                        this.fillFields();
                        this.toggleInputFields();
                    } else if (response.data != "") {
                        console.log("You've changed your profile successfully!");
                        console.log("Your updated data is--> Name: " + response.data.split("¿")[2] + " Surname: " + response.data.split("¿")[3] + " Email: " + response.data.split("¿")[1] + " Username: " + response.data.split("¿")[0]);
                        self.user.username = response.data.split("¿")[0];
                        self.user.email = response.data.split("¿")[1];
                        self.user.firstname = response.data.split("¿")[2];
                        self.user.lastname =  response.data.split("¿")[3];
                        userInfo.dispatch('changingUserInfo', response.data);
                        window.location.reload();
                    }

                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        toggleInputFields: function() {
            if (document.getElementById("editButton").text == "Edit") {
                document.getElementById("first_name").disabled = false;
                document.getElementById("last_name").disabled = false;
                document.getElementById("username").disabled = false;
                document.getElementById("email").disabled = false;
                document.getElementById("password").disabled = false;
                document.getElementById("editButton").text = "Save";
            } else {
                document.getElementById("first_name").disabled = true;
                document.getElementById("last_name").disabled = true;
                document.getElementById("username").disabled = true;
                document.getElementById("email").disabled = true;
                document.getElementById("password").disabled = true;
                document.getElementById("editButton").text = "Edit";
            }
        },
        toFormData: function(e) {
            var form_data = new FormData();
            for (var key in e){
                form_data.append(key, e[key]);
            }
            return form_data;
        }
    }
}
</script>
<style>
#profileForm {
    margin-left: 0px;
}
#chart {
    display: inline-block;
    position: relative;
    width: 950px;
    height: 550px;
    top: 10px;
    left: 0;
    overflow: auto;
    white-space: nowrap;
}
#profileMenu {
    position:fixed;
    display: block;
    width: 15%;
    height: 100%;
    top: 64px;
    background: teal;
}
.sideOption {
    position: relative;
    width: 100%;
    height: 100%;
}
a {
    color: white;
}
.mobile-fab-tip {
	position: fixed;
	right: 85px;
	padding:0px 0.5rem;
	text-align: right;
	background-color: #323232;
	border-radius: 2px;
	color: #FFF;
	width:auto;
}
.btn {
    width: 225px;
}
</style>
