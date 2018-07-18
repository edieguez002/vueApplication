import Vuex from 'vuex';
import Vue from 'vue';
import Axios from 'axios';
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: {username: sessionStorage.getItem("username"), firstname: sessionStorage.getItem("firstname"), lastname: sessionStorage.getItem("lastname")},
        logged: sessionStorage.getItem("token"),
        projects: [sessionStorage.getItem("projects")]
    },

    getters: {
        getUser(state) {
            var username = sessionStorage.getItem("username");
            var firstname = sessionStorage.getItem("firstname");
            var lastname = sessionStorage.getItem("lastname");
            let user= {
                username,
                firstname,
                lastname
            };
        },
        getUsername(state) {
            var username = sessionStorage.getItem("username");
            return username;
        },
        getFirstname(state) {
            return sessionStorage.getItem("firstname");
        },
        getLastname(state) {
            return sessionStorage.getItem("lastname");
        },
        getEmail(state) {
            return sessionStorage.getItem("email");
        },
        isLogged(state) {
            console.log(sessionStorage.getItem("token"));
            return sessionStorage.getItem("token");
        },
        getProjects(state) {
            var retrieveProjects = sessionStorage.getItem("projects");
            return JSON.parse(retrieveProjects);
        },
        getUserObjectives(state) {
            var retrieveUOs = sessionStorage.getItem("userObjectives");
            return JSON.parse(retrieveUOs);
        },
        getAproject(state, pName) {
            return pName => sessionStorage.getItem('projects').filter(project => {
                return project.name == pName
            });

        }
    },

    actions: {
        signingUserInfo({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('setUserFromSigningResponse', user);
                resolve();
            });
        },
        changingUserInfo({commit}, user) {
            return new Promise((resolve, reject) => {
                commit('setUserFromChangingResponse', user);
                resolve();
            });
        },
        changingProjectInfo({commit}, project) {
            return new Promise((resolve, reject) => {
                commit('setProjectFromChangingResponse', project);
                resolve();
            });
        },
        loggingOut({commit}) {
            return new Promise((resolve, reject) => {
                commit('logOut');
                resolve();
            });
        },
        gettingUserProjects({commit}, projects) {
            return new Promise((resolve, reject) => {
                commit('getProjects', projects);
                resolve();
            });
        },
        updatingUserProjects({commit}, projects) {
            return new Promise((resolve, reject) => {
                commit('updateProjects', projects);
                resolve();
            });
        },
        gettingUserObjectives({commit}, uos) {
            return new Promise((resolve, reject) => {
                commit('getObjectives', uos);
                resolve();
            });
        },
        updatingUserObjectives({commit}, uos) {
            return new Promise((resolve, reject) => {
                commit('updateObjectives', uos);
                resolve();
            });
        }
    },

    mutations: {
        setUserFromSigningResponse(state, user) {
            var name = user.split(" ")[0];
            var first = user.split(" ")[1];
            var last = user.split(" ")[2];
            var email = user.split(" ")[3]
            sessionStorage.setItem("username", name);
            sessionStorage.setItem("firstname", first);
            sessionStorage.setItem("lastname", last);
            sessionStorage.setItem("email", email);
            sessionStorage.setItem("token", "true");
        },
        setUserFromChangingResponse(state, user) {
            var name = user.split("¿")[0];
            var email = user.split("¿")[1];
            var first = user.split("¿")[2];
            var last = user.split("¿")[3]
            sessionStorage.setItem("username", name);
            sessionStorage.setItem("firstname", first);
            sessionStorage.setItem("lastname", last);
            sessionStorage.setItem("email", email);
        },
        setProjectFromChangingResponse(state, project) {
            var projects = JSON.parse(sessionStorage.getItem('projects'));
            console.log(projects.length);
            for (var i = 0; i < projects.length; i++) {
                console.log(project);
                if (projects[i].name == project[0]) {
                    projects[i].name = project[1];
                    projects[i].description = project[2];
                }
            }
            sessionStorage.setItem('projects', JSON.stringify(projects));
        },
        logOut(state) {
            sessionStorage.setItem("username", "");
            sessionStorage.setItem("firstname", "");
            sessionStorage.setItem("lastname", "");
            sessionStorage.setItem("email", "");
            sessionStorage.setItem("token", "false");
            sessionStorage.removeItem("projects");
            sessionStorage.removeItem("userObjectives");
        },
        getProjects(state, projects) {
            var projectsArray = [];
            projectsArray.splice(0, projectsArray.length);
            for (var i = 0; i < projects.length; i++) {
                var pros = new Object();
                pros.name = projects[i].split("?")[0];
                pros.description = projects[i].split("?")[1];
                pros.archived = projects[i].split("?")[2];
                projectsArray.push(pros);
                console.log(pros);
            }
            sessionStorage.setItem('projects', JSON.stringify(projectsArray));
        },
        updateProjects(state, projects) {
            sessionStorage.removeItem('projects');
            var projectsArray = [];
            for (var i = 0; i < projects.length; i++) {
                console.log(projects[i]);
                projectsArray.push(projects[i]);
            }
            console.log(projectsArray);
            sessionStorage.setItem('projects', JSON.stringify(projectsArray));
        },
        getObjectives(state, uos) {
            var uosArray = [];
            uosArray.splice(0, uosArray.length);
            for (var i = 0; i < uos.length-1; i++) {
                var newUO = new Object();
                newUO.uoID = uos[i].split("¿")[0];
                newUO.pName = uos[i].split("¿")[1]
                newUO.uoName = uos[i].split("¿")[2];
                newUO.uoType = uos[i].split("¿")[3];
                newUO.uoAntecesor = uos[i].split("¿")[4];
                newUO.uoSucesor = uos[i].split("¿")[5];
                newUO.description = uos[i].split("¿")[6];
                newUO.state = uos[i].split("¿")[7];
                newUO.reject = uos[i].split("¿")[8];
                newUO.inactive = uos[i].split("¿")[9];
                newUO.time = uos[i].split("¿")[10];
                uosArray.push(newUO);
            }
            sessionStorage.setItem('userObjectives', JSON.stringify(uosArray));
        },
        updateObjectives(state, uos) {
            sessionStorage.removeItem('userObjectives');
            var uosArray = [];
            for (var i = 0; i < uos.length; i++) {
                console.log(uos[i]);
                uosArray.push(uos[i]);
            }
            console.log(uosArray);
            sessionStorage.setItem('userObjectives', JSON.stringify(uosArray));
        }
    }
})
