<template>
    <div class="row">
        <form id="projectForm">
            <div class="row col s6">
                <div class="input-field">
                    <i class="material-icons prefix">work</i>
                    <input id="projectName" type="text" class="validate" v-model="project.name" />
                    <label for="projectName">Project name</label>
                </div>
            </div>
            <div class="row col s8">
                <div class="input-field">
                    <i class="material-icons prefix">note</i>
                    <textarea id="projectDescription" class="materialize-textarea" v-model="project.description"></textarea>
                    <label for="projectDescription">Description of the project (Optional)</label>
                </div>
            </div>
        </form>
        <button class="waves-effect waves-light z-depth-5 btn row col s4 offset-s4" type="submit" v-on:click="createProject">Create</button>
    </div>
</template>
<script>
import Axios from 'axios'
import userInfo from '@/store/userInfo.js'

export default {
    name: 'newProject',
    data() {
        return {
            project: {name: '', description:'' },
            currentUser: userInfo.getters.getUsername
        }
    },
    methods: {
        createProject: function() {
            if (this.project.name == "") {
                alert("You must put a name to the project.");
            } else {
                if (this.project.description == "") {
                    this.project.description = "No project description was given";
                }
                var params = this.toFormData(this.project);
                params.append('username', this.currentUser);
                console.log("This are the values that will be inserted on the projects table:");
                for(var i of params.values()) {
                    console.log(i);
                }
                Axios.post('http://localhost:80/APICRUD/Projects/saveNewProject.php', params)
                .then(function (response) {
                    if (response.data == "ERROR") {
                        alert("The name of the project already exists.");
                    } else if(response.data == "SUCCESS") {
                        console.log("Your new project has been saved successfully");
                        $('#modalNewProject').modal('close');
                        window.location.reload();
                    } else {
                        console.log(response.data);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
            this.clearForm();
        },
        toFormData: function(e) {
            var form_data = new FormData();
            for (var key in e){
                form_data.append(key, e[key]);
            }
            return form_data;
        },
        clearForm: function() {
            this.project.name = "";
            this.project.description = "";
        }
    }
}
</script>
