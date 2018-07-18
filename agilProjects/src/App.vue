<template>
  <div id="app">
      <template v-if="!logged">
          <nav>
              <div class="nav-wrapper grey darken-3">
                  <router-link :to="{ name: 'App', params: {} }"><img class="responsive-img left" width="90px" height="50px" src="./assets/agilProjects_icon.png"/></router-link>
                  <a href="#" data-activates="mobile-demo" class="button-collapse right"><i class="material-icons">menu</i></a>
                  <ul id="nav-mobile" class="right teal hide-on-med-and-down">
                      <li>
                          <a class="modal-trigger" href="#modalAccess">Enter</a>
                      </li>
                  </ul>
                  <ul id="mobile-demo" class="side-nav">
                      <li>
                          <a class="modal-trigger" href="#modalAccess">Enter</a>
                      </li>
                  </ul>
              </div>
          </nav>
          <h1>{{ this.msg }}</h1>
          <div id="modalAccess" class="modal">
              <div class="modal-content">
                  <div>
                      <i class="material-icons prefix right modal-close">close</i>
                  </div>
                  <div>
                      <Access></Access>
                  </div>
              </div>
          </div>
      </template>

      <template v-else>
          <div class="navbar-fixed">
              <nav>
                  <div class="nav-wrapper grey darken-3">
                      <router-link :to="{ name: 'App', params: {} }" v-on:click.native="showTable(true, '')"><img class="responsive-img left" width="90px" height="50px" src="./assets/agilProjects_icon.png" href="/"/></router-link>
                      <a href="#" data-activates="mobile-demo" class="button-collapse right"><i class="material-icons">menu</i></a>
                      <ul id="nav-mobile" class="right teal hide-on-med-and-down">
                          <li>
                              <a class="dropdown-button" href="#" data-activates="projectsDropdown"><i class="material-icons left">keyboard_arrow_down</i>Active Projects</a>
                              <ul id="projectsDropdown" class="dropdown-content" v-if="projects.length > 0">
                                  <li v-for="project in projects">
                                      <router-link v-bind:to="'/projects/' + project.name" v-on:click.native="showTable(false, project.name)">{{ project.name }}</router-link>
                                  </li>
                              </ul>
                              <ul id="projectsDropdown" class="dropdown-content" v-else>
                                  <li>
                                      <a>"No active projects"</a>
                                  </li>
                              </ul>
                          </li>
                          <li>
                              <a class="modal-trigger" href="#modalNewProject">New project...</a>
                          </li>
                          <li>
                              <a class="dropdown-button" href="#" data-activates="userDropdown">{{ currentUser.firstname }} {{ currentUser.lastname }}</a>
                              <ul id="userDropdown" class="dropdown-content">
                                  <li>
                                      <router-link v-bind:to="'/' + this.currentUser.username"><i class="material-icons">account_circle</i>Profile</router-link>
                                  </li>
                                  <li>
                                      <a href="/" v-on:click="logOut"><i class="material-icons">exit_to_app</i>Logout</a>
                                  </li>
                              </ul>
                          </li>
                      </ul>
                  </div>
              </nav>
          </div>
          <ul id="mobile-demo" class="side-nav">
              <li class="divider teal"></li>
              <li class="teal">
                  <a>Active projects</a>
              </li>
              <li v-for="project in projects">
                  <router-link v-bind:to="'/projects/' + project.name" v-on:click.native="showTable(false, project.name)" class="teal lighten-5"><i class="material-icons">assignment</i>{{ project.name }}</router-link>
              </li>
              <li>
                  <a class="modal-trigger teal" href="#modalNewProject">New project...</a>
              </li>
              <li class="divider teal"></li>
              <li class="teal">
                  <a>{{ currentUser.firstname }} {{ currentUser.lastname }}</a>
              </li>
              <li class="teal lighten-5">
                  <router-link v-bind:to="'/' + this.currentUser.username"><i class="material-icons">account_circle</i>Profile</router-link>
              </li>
              <li class="teal lighten-5">
                  <a href="/" v-on:click="logOut"><i class="material-icons">exit_to_app</i>Logout</a>
              </li>
          </ul>

          <!--<h4>Current user: {{ currentUser.username }}</h4>
          <h4>  Name: {{ currentUser.firstname }}</h4>
          <h4>  Surname: {{ currentUser.lastname }}</h4>
          <h4>Logged? {{ logged }}</h4>-->

          <!-- Table to show the projects  of the user -->
          <div v-if="showingTable">
              <div id="projectTable">
                  <h1>{{ this.msg }}</h1>
                  <ul class="collapsible" data-collapsible="accordion" v-if="projects.length > 0">
                      <li v-for="project in projects">
                          <!-- FOR ARCHIVED PROJECTS-->
                          <template v-if="project.archived == 1">
                              <div class="collapsible-header blue lighten-3">
                                  <i class="material-icons">folder</i>
                                  {{ project.name }}
                              </div>
                              <div class="collapsible-body blue lighten-4">
                                  <div class="col s2 left">
                                      <span>{{ project.description }}</span>
                                  </div>
                                  <router-link v-bind:to="'/projects/' + project.name" class="waves-effect waves-light z-depth-5 btn " v-on:click.native="showTable(false, project.name)">Open</router-link>
                                  <a class="waves-effect waves-light z-depth-5 btn " v-on:click="unarchiveProject(project)">Unarchive</a>
                                  <a class="waves-effect waves-light z-depth-5 btn red lighten-1" v-on:click="deleteProject(project)">Delete</a>
                              </div>
                          </template>
                          <!-- FOR ACTIVE PROJECTS -->
                          <template v-else>
                              <div class="collapsible-header green lighten-3">
                                  <i class="material-icons">folder_open</i>
                                  {{ project.name }}
                              </div>
                              <div class="collapsible-body green lighten-4">
                                  <div class="col s2 left">
                                      <span>{{ project.description }}</span>
                                  </div>
                                  <router-link v-bind:to="'/projects/' + project.name" class="waves-effect waves-light z-depth-5 btn " v-on:click.native="showTable(false, project.name)">Open</router-link>
                                  <a class="waves-effect waves-light z-depth-5 btn " v-on:click="archiveProject(project)">Archive</a>
                                  <a class="waves-effect waves-light z-depth-5 btn red lighten-1" v-on:click="deleteProject(project)">Delete</a>
                              </div>
                          </template>
                      </li>
                  </ul>
                  <ul class="collapsible" data-collapsible="accordion" v-else>
                      <li>
                          <div class="collapsible-header red lighten-4">
                              No projects available.
                          </div>
                          <div class="collapsible-body teal lighten-3">
                              To see projects here just create one clicking on "New project..." button.
                          </div>
                      </li>
                  </ul>
              </div>
          </div>

          <div id="modalNewProject" class="modal">
              <div>
                  <i id="rebelCross" class="material-icons prefix right modal-close">close</i>
              </div>
              <div class="modal-content">

                  <div class="left">
                      <h4>Creating new project...</h4>
                  </div>
                    <NewProject id="newProjectForm"></NewProject>
              </div>
          </div>
          <router-view/>
      </template>

  </div>
</template>

<script>
import Vue from 'vue'
import Axios from 'axios'
import Access from '@/components/Access.vue'
import NewProject from '@/components/NewProject.vue'
import seeExistingProject from '@/components/seeExistingProject.vue'
import Profile from '@/components/Profile.vue'
import userInfo from '@/store/userInfo.js'
import globalMethods from '@/assets/globalMethods'

export default {
  name: 'App',
  components: { Access, NewProject, seeExistingProject },
  data() {
      return {
          msg: "",
          currentUser: {
            username: userInfo.getters.getUsername,
            firstname: userInfo.getters.getFirstname,
            lastname: userInfo.getters.getLastname
          },
          projects: []
      }
  },
  computed: {
    logged: function() {
        if(userInfo.getters.isLogged == "true") {
            //console.log(userInfo.getters.getProjects);
            this.msg = "These are your projects:";
            //console.log(userInfo.getters.getUsername);
            return true;
        } else {
            this.msg = "The way to manage your projects";
            return false;
        }
    },
    showingTable: function() {
        if (this.$route.path == '/') {
            return true;
        } else {
            return false;
        }
    }
},
  mounted: function() {
      this.getUserProjectsInfo();
      $('.modal').modal();
      $(".button-collapse").sideNav({
          edge: 'right',
          draggable: true,
          closeOnClick: true
      });
      $(".dropdown-button").dropdown({
          belowOrigin: true,
          constrainWidth: false,
          hover: true
      });
      $('.collapsible').collapsible();
  },
  methods: {
      // The function "getUserProjectsInfo" will be still available in this file in case of necessity
      getUserProjectsInfo: function() {
          this.projects = [];
          var params = this.toFormData(this.currentUser);
          var self = this;
          Axios.post('http://localhost:80/APICRUD/Projects/getUserProjects.php', params)
          .then(function (response){
              var proInfo = response.data.split("/");
              proInfo.length--;
              for (var i = 0; i < proInfo.length; i++) {
                  var newProject = new Object();
                  newProject.name = proInfo[i].split("?")[0];
                  newProject.description = proInfo[i].split("?")[1];
                  newProject.archived = proInfo[i].split("?")[2];
                  self.projects.push(newProject);
              }
              userInfo.dispatch('gettingUserProjects', proInfo);
              self.getUserObjectives();
          })
          .catch(function (error) {
              console.log(error);
          });
      },
      getUserObjectives: async function() {
          var info = {username: userInfo.getters.getUsername};
          var params = this.toFormData(info);
          var self = this;
          var result = await Axios.post("http://localhost:80/APICRUD/Projects/getUserObjectives.php", params)
          .then(function (response) {
              console.log("These are the projects user objectives: ");
              if (response.data == "") {
                  userInfo.dispatch('updatingUserObjectives', []);
                  console.log("You haven't got any user objective yet. :(");
              } else if (response.data == "ERROR") {
                  console.log("There was an error getting the user objectives...");
              } else if (response.data != "") {
                  self.userObjectives = [];
                  var uos = response.data.split("/");
                  userInfo.dispatch('gettingUserObjectives', uos);
              }
          })
          .catch(function (error) {
              console.log(error);
          });

      },
      archiveProject: function(p) {
          var info = {username: this.currentUser.username, pname: p.name};
          console.log(info);
          var params = this.toFormData(info);
          var self = this;
          Axios.post('http://localhost:80/APICRUD/Projects/archiveProject.php', params)
          .then(function (response){
              if (response.data == "ERROR") {
                  console.log("An error occured archiving the project...");
              } else if (response.data == "SUCCESS") {
                  for (var i = 0; i < self.projects.length; i++) {
                      if (self.projects[i].name == p.name ) {
                          self.projects[i].archived = 1;
                      }
                  }
                  userInfo.dispatch('updatingUserProjects', self.projects);
              }
          })
          .catch(function (error) {

          });
          console.log(this.projects);
      },
      unarchiveProject: function(p) {
          var info = {username: this.currentUser.username, pname: p.name};
          console.log(info);
          var params = this.toFormData(info);
          var self = this;
          Axios.post('http://localhost:80/APICRUD/Projects/unarchiveProject.php', params)
          .then(function (response){
              if (response.data == "ERROR") {
                  console.log("An error occured archiving the project...");
              } else if (response.data == "SUCCESS") {
                  for (var i = 0; i < self.projects.length; i++) {
                      if (self.projects[i].name == p.name ) {
                          self.projects[i].archived = 0;
                      }
                  }
                  userInfo.dispatch('updatingUserProjects', self.projects);
              }
          })
          .catch(function (error) {

          });
          console.log(this.projects);
      },
      deleteProject: function(p) {
          var info = {username: this.currentUser.username, pname: p.name};
          var params = this.toFormData(info);
          var self = this;
          Axios.post('http://localhost:80/APICRUD/Projects/deleteProject.php', params)
          .then(function (response){
              if (response.data == "ERROR") {
                  console.log("An error occured deleting the project...");
              } else if (response.data == "SUCCESS") {
                  for (var i = 0; i < self.projects.length; i++) {
                      if (self.projects[i].name == p.name) {
                          self.projects.splice(self.projects.indexOf(self.projects[i]),1);
                      }
                  }
              }
          })
          .catch(function (error) {

          });
          console.log(this.projects);
      },
      logOut: function() {
          userInfo.dispatch('loggingOut');
      },
      toFormData: function(e) {
          var form_data = new FormData();
          for (var key in e){
              form_data.append(key, e[key]);
          }
          return form_data;
      },
      showTable: function(status, pName) {
          if (status) {
              window.location.reload();
          }
      }
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0px;
}
#rebelCross {
    margin-top: 20px;
    margin-right: 30px;
}
#newProjectForm {
    margin-top: 85px;
}
.link line {
  stroke: #696969;
}

.link line.separator {
  stroke: #fff;
  stroke-width: 2px;
}

.node circle {
  stroke: #000;
  stroke-width: 1.5px;
}

.node text {
  font: 10px sans-serif;
  pointer-events: none;
}
</style>
