    <template>
        <div id="projectColumns"class="row">

            <!-- Side nav to manage different actions of the project -->
                <nav id="projectMenu" class="nav-extended hide-on-med-and-down">
                    <ul id="actionList">
                        <li class="sideOption" v-if="this.project.archived == 0">
                            <a class="modal-trigger" href="#modalNewUserObjective">New user objective...</a>
                        </li>
                        <li class="sideOption" v-if="this.project.archived == 0">
                            <a v-on:click="archiveProject">Archive project</a>
                        </li>
                        <li class="sideOption" v-if="this.project.archived == 1">
                            <a v-on:click="unarchiveProject">Unarchive project</a>
                        </li>
                        <li class="sideOption">
                            <a v-on:click="deleteProject">Delete project</a>
                        </li>
                    </ul>
                </nav>

            <!-- Flat action button -->
            <div class="fixed-action-btn click-to-toggle hide-on-large-only">
                <a class="btn-floating btn-large teal">
                  <i class="large material-icons">poll</i>
                </a>
                <ul>
                    <li v-if="this.project.archived == 0">
                        <a class="btn-floating lighten-1 modal-trigger" href="#modalNewUserObjective"><i class="material-icons">add_circle_outline</i></a>
                        <a class="btn-floating mobile-fab-tip modal-trigger" href="#modalNewUserObjective">New user objective...</a>
                    </li>
                    <li v-if="this.project.archived == 0">
                        <a class="btn-floating lighten-1" v-on:click="archiveProject"><i class="material-icons">archive</i></a>
                        <a class="btn-floating mobile-fab-tip" v-on:click="archiveProject">Archive project</a>
                    </li>
                    <li v-if="this.project.archived == 1">
                        <a class="btn-floating lighten-1" v-on:click="unarchiveProject"><i class="material-icons">unarchive</i></a>
                        <a class="btn-floating mobile-fab-tip" v-on:click="unarchiveProject">Unarchive project</a>
                    </li>
                    <li>
                        <a class="btn-floating red lighten-1" v-on:click="deleteProject"><i class="material-icons">delete</i></a>
                        <a class="btn-floating mobile-fab-tip red lighten-1" v-on:click="deleteProject">Delete project</a>
                    </li>
                </ul>
            </div>

            <!-- Project information form -->
            <div id="projectGraph" class="col s10 push-s2">
                <div class="row col s12">
                    <div class="card grey darken-3">
                        <div class="card-panel grey darken-3 grey-text text-lighten-2" v-if="!projectForm">
                            <span class="card-title left">{{ project.name }}</span>
                            <br />
                            <p class="center">{{ project.description }}</p>
                            <br />
                        </div>
                        <div class="card-panel grey darken-3" v-else>
                            <form>
                                <div class="input-field">
                                      <input id="name" type="text" class="validate grey-text text-lighten-2" v-model="newProject.name">
                                      <label class="active grey-text text-lighten-2" for="name">Project name</label>
                                </div>
                                <div class="input-field">
                                      <input id="description" type="text" class="validate grey-text text-lighten-2" v-model="newProject.description">
                                      <label class="active grey-text text-lighten-2" for="description">Description</label>
                                </div>
                            </form>
                        </div>
                        <div class="card-action">
                            <a id="editButton" class="btn teal darken-1 z-depth-5"  v-if="project.archived == 0" v-on:click="toggleForm">Edit</a>
                        </div>
                    </div>
                </div>
                <!-- Project visualization and user objectives info detailed in a form -->
                <br />
                <div class="row card-panel yellow lighten-3 col s12" v-if="warnings" v-for="msgs in msg">
                    <p class="left"> <b>WARNING!</b> {{ msgs }} </p>
                </div>
                <div id="project-canvas" class="row col s12 card-panel teal lighten-5 z-depth-3">
                    <svg width="100" height="130" v-for="activity in activities">
                        <text class="custom" dx="50" dy="30" alignment-baseline="middle" text-anchor="middle">{{ activity }}</text>
                    </svg>

                    <div id="projectDB" class="row col s3">
                        <div class="card white z-depth-4">
                            <a class="card-title black-text">User objective</a>
                            <form id="objetiveForm" class="card-content white" v-show="uoForm || userObjectives.length > 0">
                                <div class="row">
                                    <div class="input-field col s4">
                                          <input disabled id="uoid" type="text" class="validate black-text" v-model="newUserObjective.uoId">
                                          <label class="active teal-text" for="uoid">ID</label>
                                    </div>
                                    <div class="input-field col s8">
                                          <select id="uostate">
                                              <option disabled selected>Pool of ideas</option>
                                              <option disabled>A1</option>
                                              <option disabled>A2</option>
                                              <option disabled>A3</option>
                                              <option disabled>Done</option>
                                          </select>
                                          <label class="teal-text">Activity</label>
                                    </div>
                                </div>
                                <div class="input-field">
                                      <input disabled id="uoname" type="text" class="validate black-text" v-model="newUserObjective.uoName">
                                      <label class="active teal-text" for="uoname">Objective name</label>
                                </div>
                                <div class="input-field">
                                      <input disabled id="uotype" type="text" class="validate black-text" v-model="newUserObjective.uoType">
                                      <label class="active teal-text" for="uotype">Type of objective</label>
                                </div>
                                <div class="input-field">
                                      <textarea disabled id="uodescription" type="text" class="materialize-textarea validate black-text" v-model="newUserObjective.uoDescription"></textarea>
                                      <label class="active teal-text" for="uodescription">Description of objective</label>
                                </div>
                                <div class="input-field">
                                    <input disabled id="time" type="text" class="validate black-text" v-model="newUserObjective.time"/>
                                    <label id="timeLabel" class="active teal-text" for="time">Total time invested (hours)</label>
                                </div>
                                <div class="row">
                                    <a id="disableUOBtn" class="btn grey white-text" v-if="uoDisabled" v-on:click="disableUserObjective">Disable</a>
                                    <a id="disableUOBtn" class="btn grey white-text" v-if="uoEnabled" v-on:click="enableUserObjective">Enable</a>
                                    <a id="editUOBtn" class="btn white-text" v-if="uoForm" v-on:click="editUserObjective">Edit</a>
                                </div>
                                <div class="row">
                                    <a id="rejectUOBtn" class="btn white-text red lighten-1" v-if="uoRejected" v-on:click="rejectUserObjective">Reject</a>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div v-if="userObjectives.length == 0">
                        <span>There are no user objectives yet... Create one so as to see the graphic.</span>
                    </div>
                </div>

                <!-- Modal for the creation of a new user objective -->
                <div id="modalNewUserObjective" class="modal">
                    <div>
                        <i id="modalClose" class="material-icons prefix right modal-close">close</i>
                    </div>
                    <div class="modal-content">
                        <div class="left">
                            <h4>Creating new user objective...</h4>
                        </div>
                        <div>
                            <form id="userObjectiveForm">
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="userObjectiveName" type="text" class="validate"/>
                                        <label for="userObjectiveName">User objective name</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <select id="newUserType">
                                            <option selected>Directo</option>
                                            <option>Division</option>
                                            <option>Fusion</option>
                                            <option>Reutilizado</option>
                                            <option>Incremento</option>
                                            <option>Incrementado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select id="multipleSelect" multiple>
                                            <option disabled selected>Choose your objectives</option>
                                            <!--<option v-for="uo in userObjectives">Objective {{ uo.uoID }}</option>-->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea id="userObjectiveDescription" class="materialize-textarea"></textarea>
                                        <label for="userObjectiveDescription">Description of the user objective (Optional)</label>
                                    </div>
                                </div>
                            </form>
                            <button class="waves-effect waves-light z-depth-5 btn row col s4 offset-s4" type="submit" v-on:click="addUserObjective">Create</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </template>
    <script>
    import Axios from 'axios'
    import userInfo from '@/store/userInfo.js'

    export default {
        name: 'seeExistingProject',

        data() {
            return {
                msg: [],
                currentProject: "",
                currentUser: userInfo.getters.getUsername,
                project: {},
                newProject: {
                    name: "",
                    description: ""
                },
                projectForm: false,
                uoForm: false,
                uoRejected: false,
                uoDisabled: false,
                uoEnabled: false,
                warnings: false,
                userObjectives: userInfo.getters.getUserObjectives,
                newUserObjective: {
                    uoId: 0,
                    uoType: " ",
                    uoName: " ",
                    uoState: " ",
                    uoPredecessor: " ",
                    uoSucessor: " ",
                    uoDescription: "No description",
                    time: "0.0",
                    uoReject: 0,
                    uoInactive: 0
                },
                activities: ["Pool of ideas", "A1", "A2", "A3", "Done"],
                overallLength:0
            }
        },
        watch: {
                '$route.params.id': function (id) {
                    this.warnings = false;
                    this.init();
                    d3.selectAll("g").remove();
                    if (this.userObjectives.length > 0) {
                        this.initChart();
                    } else {
                        d3.selectAll("svg")
                            .attr("height", 130);
                        d3.selectAll("g").remove();
                    }
                    $('select').material_select();
                    $('.modal').modal();
                }
        },
        mounted: function() {

            this.init();
            if (this.userObjectives.length > 0) {
                this.initChart();
            } else {
                d3.selectAll("g").remove();
            }
            $('select').material_select();
            $('.modal').modal();

        },
        methods: {
            init: function() {
                console.log(this.userObjectives.length);
                this.newUserObjective.uoId = 0;
                this.newUserObjective.uoType = " ";
                this.newUserObjective.uoName = " ";
                this.newUserObjective.uoState = " ";
                this.newUserObjective.uoPredecessor = " ";
                this.newUserObjective.uoSucessor = " ";
                this.newUserObjective.uoDescription = "No description";
                this.newUserObjective.uoInactive = 0;
                this.newUserObjective.uoReject = 0;
                this.uoForm = false;
                var projects = userInfo.getters.getProjects;
                for (var i = 0; i<projects.length; i++) {
                    if (projects[i].name == this.$route.params.id) {
                        this.project = projects[i];
                    }
                }
                console.log(this.project);
                this.userObjectives = [];

                for (var i = 0; i < userInfo.getters.getUserObjectives.length; i++) {
                    if (this.project.name == userInfo.getters.getUserObjectives[i].pName) {
                        this.userObjectives.push(userInfo.getters.getUserObjectives[i]);
                    }
                }
                $('#multipleSelect').find("option").remove().end().append("<option disabled selected>Choose your objectives</option>");
                for (var i = 0; i < this.userObjectives.length; i++) {
                    if (this.userObjectives[i].reject == 0) {
                        var option = d3.select("#multipleSelect")
                            .append("option")
                            .attr("id", this.userObjectives[i].uoID);
                        option.text("Objective " + this.userObjectives[i].uoID);
                    }
                }
                // HERE PUT WARNINGS OF DIVISION INTO THE MESSAGE
                var warningUO = [];
                for (var i = 0; i < this.userObjectives.length; i++) {
                    if (this.project.name == this.userObjectives[i].pName && this.userObjectives[i].uoType == "Division") {
                        warningUO.push(this.userObjectives[i].uoAntecesor);
                    }
                }
                var k = 0;
                for (var i = 0; i < warningUO.length; i++) {
                    for (var j = 0; j < this.userObjectives.length; j++) {
                        if (this.userObjectives[j].uoID == warningUO[i].split(",")[0] && this.userObjectives[j].uoSucesor.split(",").length == 1) {
                            this.msg[k] = " The user objective " + this.userObjectives[j].uoID + " only has one sucessor of type division: " + this.userObjectives[j].uoSucesor;
                            k++;
                            this.warnings = true;
                        }
                    }
                }

                // Remove old div in order to create the new chart
                /*var oldiv = document.getElementById("chart");
                oldiv.parentNode.removeChild(oldiv);
                var newDiv = document.createElement("div");
                newDiv.setAttribute("id", "chart");
                document.getElementById("projectGraph").appendChild(newDiv);*/

            },
            initChart: function() {
                var ids = [];
                for (var i = 0; i < this.userObjectives.length; i++) {
                    ids[i] = this.userObjectives[i].uoID;
                    console.log(ids[i]);
                }
                var maxLength = this.userObjectives.length;
                console.log(this.userObjectives);
                d3.selectAll("g").remove();
                d3.selectAll("defs").remove();
                d3.selectAll("svg")
                    .data(this.activities)
                    .attr("id", function(d) { return d; })
                    .attr("height", function () {
                        return 130 * maxLength;
                    });
                for (var i = 0; i < this.activities.length; i++) {
                    var position = 0;
                    for (var k = 0; k < this.userObjectives.length; k++) {
                        if (this.activities[i] == this.userObjectives[k].state) {
                            switch (this.userObjectives[k].uoType) {
                                case "Directo":
                                    var g = d3.select(document.getElementById(this.activities[i]))
                                        .append("g")
                                        .attr("style", "cursor: pointer;")
                                        .data([this.userObjectives[k].uoID]);
                                    var circleDirecto = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 30)
                                        .attr("stroke", "black")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    if (this.userObjectives[k].inactive == 0 && this.userObjectives[k].reject == 0) {
                                        circleDirecto.attr("fill", "white");
                                    } else if (this.userObjectives[k].inactive == 1) {
                                        circleDirecto.attr("fill", "#C2C0C0");
                                    } else if (this.userObjectives[k].reject == 1) {
                                        circleDirecto.attr("fill", "#FF4848");
                                    }
                                    g.append("text")
                                        .attr("dx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("dy", (position * 80) + 80)
                                        .attr("alignment-baseline", "middle")
                                        .attr("text-anchor", "middle")
                                        .on("click", this.showUserObjective)
                                        .text(this.userObjectives[k].uoID);
                                    position++;
                                    break;
                                case "Fusion":
                                    var g = d3.select(document.getElementById(this.activities[i]))
                                        .append("g")
                                        .attr("style", "cursor: pointer;")
                                        .data([this.userObjectives[k].uoID]);
                                    var circleFusion = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 30)
                                        .attr("stroke", "black")
                                        .attr("stroke-width", 3)
                                        .on("click", this.showUserObjective);
                                    if (this.userObjectives[k].inactive == 0 && this.userObjectives[k].reject == 0) {
                                        circleFusion.attr("fill", "white");
                                    } else if (this.userObjectives[k].inactive == 1) {
                                        circleFusion.attr("fill", "#C2C0C0");
                                    } else if (this.userObjectives[k].reject == 1) {
                                        circleFusion.attr("fill", "#FF4848");
                                    }
                                    g.append("text")
                                        .attr("dx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("dy", (position * 80) + 80)
                                        .attr("alignment-baseline", "middle")
                                        .attr("text-anchor", "middle")
                                        .on("click", this.showUserObjective)
                                        .text(this.userObjectives[k].uoID);
                                    position++;
                                    break;
                                case "Division":
                                    var g = d3.select(document.getElementById(this.activities[i]))
                                        .append("g")
                                        .attr("style", "cursor: pointer;")
                                        .data([this.userObjectives[k].uoID]);
                                    var circleDivision = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 30)
                                        .attr("stroke", "black")
                                        .attr("stroke-width", 3)
                                        .attr("stroke-dasharray", "5 5")
                                        .on("click", this.showUserObjective);
                                    if (this.userObjectives[k].inactive == 0 && this.userObjectives[k].reject == 0) {
                                        circleDivision.attr("fill", "white");
                                    } else if (this.userObjectives[k].inactive == 1) {
                                        circleDivision.attr("fill", "#C2C0C0");
                                    } else if (this.userObjectives[k].reject == 1) {
                                        circleDivision.attr("fill", "#FF4848");
                                    }
                                    g.append("text")
                                        .attr("dx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("dy", (position * 80) + 80)
                                        .attr("alignment-baseline", "middle")
                                        .attr("text-anchor", "middle")
                                        .on("click", this.showUserObjective)
                                        .text(this.userObjectives[k].uoID);
                                    position++;
                                    break;
                                case "Reutilizado":
                                    var g = d3.select(document.getElementById(this.activities[i]))
                                        .append("g")
                                        .attr("style", "cursor: pointer;")
                                        .data([this.userObjectives[k].uoID]);
                                    var reutilizadoOuter = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 30)
                                        .attr("stroke", "black")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    var reutilizadoInner = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 17)
                                        .attr("stroke", "black")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    if (this.userObjectives[k].inactive == 0 && this.userObjectives[k].reject == 0) {
                                        reutilizadoOuter.attr("fill", "white");
                                        reutilizadoInner.attr("fill", "white");
                                    } else if (this.userObjectives[k].inactive == 1) {
                                        reutilizadoOuter.attr("fill", "#C2C0C0");
                                        reutilizadoInner.attr("fill", "#C2C0C0");
                                    } else if (this.userObjectives[k].reject == 1) {
                                        reutilizadoOuter.attr("fill", "#FF4848");
                                        reutilizadoInner.attr("fill", "#FF4848");
                                    }
                                    g.append("text")
                                        .attr("dx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("dy", (position * 80) + 80)
                                        .attr("alignment-baseline", "middle")
                                        .attr("text-anchor", "middle")
                                        .on("click", this.showUserObjective)
                                        .text(this.userObjectives[k].uoID);
                                    position++;
                                    break;
                                case "Incrementado":
                                    var defs = d3.select(document.getElementById(this.activities[i]))
                                        .append("defs");

                                    var linear = defs.append("linearGradient")
                                        .attr("id", this.userObjectives[k].uoType)
                                        .attr("x1", "100%")
                                        .attr("y1", "0%")
                                        .attr("x2", "0%")
                                        .attr("y2", "0%");
                                    linear.append("stop")
                                        .attr("offset", "50%")
                                        .attr("style", "stop-color:black");
                                    var incrementadoOuter = linear.append("stop")
                                        .attr("offset", "50%");

                                    var g = d3.select(document.getElementById(this.activities[i]))
                                        .append("g")
                                        .attr("style", "cursor: pointer;")
                                        .data([this.userObjectives[k].uoID]);
                                    g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 30)
                                        .attr("fill", "url(#" + this.userObjectives[k].uoType + ")")
                                        .attr("stroke", "black")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    var incrementadoInner = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 17)
                                        .attr("stroke", "none")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    if (this.userObjectives[k].inactive == 0 && this.userObjectives[k].reject == 0) {
                                        incrementadoOuter.attr("style", "stop-color:white")
                                        incrementadoInner.attr("fill", "white");
                                    } else if (this.userObjectives[k].inactive == 1) {
                                        incrementadoOuter.attr("style", "stop-color:#C2C0C0")
                                        incrementadoInner.attr("fill", "#C2C0C0");
                                    } else if (this.userObjectives[k].reject == 1) {
                                        incrementadoOuter.attr("style", "stop-color:#FF4848")
                                        incrementadoInner.attr("fill", "#FF4848");
                                    }
                                    g.append("text")
                                        .attr("dx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("dy", (position * 80) + 80)
                                        .attr("alignment-baseline", "middle")
                                        .attr("text-anchor", "middle")
                                        .on("click", this.showUserObjective)
                                        .text(this.userObjectives[k].uoID);
                                    position++;
                                    break;
                                case "Incremento":
                                    var defs = d3.select(document.getElementById(this.activities[i]))
                                        .append("defs");

                                    var linear = defs.append("linearGradient")
                                        .attr("id", this.userObjectives[k].uoType)
                                        .attr("x1", "100%")
                                        .attr("y1", "0%")
                                        .attr("x2", "0%")
                                        .attr("y2", "0%");
                                    linear.append("stop")
                                        .attr("offset", "50%")
                                        .attr("style", "stop-color:black");
                                    var incrementoOuter = linear.append("stop")
                                        .attr("offset", "50%");

                                    var g = d3.select(document.getElementById(this.activities[i]))
                                        .append("g")
                                        .attr("style", "cursor: pointer;")
                                        .data([this.userObjectives[k].uoID]);
                                    g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 30)
                                        .attr("fill", "url(#" + this.userObjectives[k].uoType + ")")
                                        .attr("stroke", "none")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    var incrementoInner = g.append("circle")
                                        .attr("id", this.userObjectives[k].uoID)
                                        .attr("cx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("cy", (position * 80) + 80)
                                        .attr("r", 17)
                                        .attr("stroke", "none")
                                        .attr("stroke-width", 5)
                                        .on("click", this.showUserObjective);
                                    if (this.userObjectives[k].inactive == 0 && this.userObjectives[k].reject == 0) {
                                        incrementoOuter.attr("style", "stop-color:white")
                                        incrementoInner.attr("fill", "white");
                                    } else if (this.userObjectives[k].inactive == 1) {
                                        incrementoOuter.attr("style", "stop-color:#C2C0C0")
                                        incrementoInner.attr("fill", "#C2C0C0");
                                    } else if (this.userObjectives[k].reject == 1) {
                                        incrementoOuter.attr("style", "stop-color:#FF4848")
                                        incrementoInner.attr("fill", "#FF4848");
                                    }
                                    g.append("text")
                                        .attr("dx", document.getElementById(this.activities[i]).width.baseVal.value / 2)
                                        .attr("dy", (position * 80) + 80)
                                        .attr("alignment-baseline", "middle")
                                        .attr("text-anchor", "middle")
                                        .on("click", this.showUserObjective)
                                        .text(this.userObjectives[k].uoID);
                                    position++;
                                    break;
                                default:

                            }

                        }
                    }
                    if (position > this.overallLength) {
                        this.overallLength = position;
                    }
                }
                d3.selectAll("svg")
                    .attr("height", this.overallLength * 130);
            },
            showUO: function(d) {
                console.log("Hi!");
                console.log(d);
            },
            archiveProject: function() {
                var info = {username: this.currentUser, pname: this.project.name};
                this.uoForm = false;
                console.log(info);
                var params = this.toFormData(info);
                var self = this;
                Axios.post('http://localhost:80/APICRUD/Projects/archiveProject.php', params)
                .then(function (response){
                    if (response.data == "ERROR") {
                        console.log("An error occured archiving the project...");
                    } else if (response.data == "SUCCESS") {
                        self.project.archived = 1;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
                console.log(this.project);
            },
            unarchiveProject: function() {
                var info = {username: this.currentUser, pname: this.project.name};
                console.log(info);
                var params = this.toFormData(info);
                var self = this;
                Axios.post('http://localhost:80/APICRUD/Projects/unarchiveProject.php', params)
                .then(function (response){
                    if (response.data == "ERROR") {
                        console.log("An error occured archiving the project...");
                    } else if (response.data == "SUCCESS") {
                        self.project.archived = 0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
                console.log(this.project);
            },
            deleteProject: function(p) {
                var info = {username: this.currentUser, pname: this.project.name};
                var params = this.toFormData(info);
                var self = this;
                Axios.post('http://localhost:80/APICRUD/Projects/deleteProject.php', params)
                .then(function (response){
                    console.log(response);
                    if (response.data == "ERROR") {
                        console.log("An error occured deleting the project...");
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
                window.location.href = "/";
            },
            addUserObjective: function() {
                var subObjectivesArray = $('#multipleSelect').val();
                var predecessors = "";
                if (subObjectivesArray.length != 0) {
                    for (var i = 0; i < subObjectivesArray.length; i++) {
                        if (i == 0) {
                            predecessors = subObjectivesArray[i].split(" ")[1];
                        } else if (predecessors != "" && i < subObjectivesArray.length - 1) {
                            predecessors = predecessors + "," + subObjectivesArray[i].split(" ")[1];
                        } else {
                            if (predecessors == "") {
                                predecessors = subObjectivesArray[i].split(" ")[1];
                            } else {
                                predecessors = predecessors + "," + subObjectivesArray[i].split(" ")[1];
                            }
                        }
                    }
                }
                var lastUO = this.userObjectives.length + 1;
                console.log(lastUO);
                /*for (var i = 0; i < this.userObjectives.length; i++) {
                    if (this.project.name == this.userObjectives[i].pName && lastUO < this.userObjectives[i].uoID) {
                        lastUO = this.userObjectives[i].uoID;
                    }
                }*/

                var newUO = {
                    username: this.currentUser,
                    pName: this.project.name,
                    uoID: lastUO,
                    uoName: document.getElementById("userObjectiveName").value,
                    uoType: document.getElementById("newUserType").value,
                    uoAntecesor: predecessors,
                    description: document.getElementById("userObjectiveDescription").value,
                    time: 0.0
                };
                console.log(newUO);
                if (newUO.uoType == "Directo" || newUO.uoType == "Reutilizado" || newUO.uoType == "Incremento") {
                    var params = this.toFormData(newUO);
                    Axios.post('http://localhost:80/APICRUD/Projects/createUserObjectiveWithoutRelation.php', params)
                    .then( function (response) {
                        if (response.data != "ERROR") {
                            var uos = response.data.split("/");
                            var uosArray = [];
                            uosArray.splice(0, uosArray.length);
                            for (var i = 0; i < uos.length - 1; i++) {
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
                            userInfo.dispatch('updatingUserObjectives', uosArray);
                        } else {
                            alert("An error occured during the user objective type change.");
                        }
                    })
                    .catch( function (error) {
                        console.log(error);
                    });
                    window.location.reload();

                } else if (newUO.uoType == "Division") {
                    if (subObjectivesArray.length == 1) {
                        var params = this.toFormData(newUO);
                        Axios.post('http://localhost:80/APICRUD/Projects/createUserObjectiveDivisionRelation.php', params)
                        .then( function (response) {
                            console.log(response.data);
                            if (response.data != "ERROR") {
                                var uos = response.data.split("/");
                                var uosArray = [];
                                uosArray.splice(0, uosArray.length);
                                for (var i = 0; i < uos.length - 1; i++) {
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
                                userInfo.dispatch('updatingUserObjectives', uosArray);
                            } else {
                                alert("An error occured during the user objective type change.");
                            }
                        })
                        .catch( function (error) {
                            console.log(error);
                        });
                        window.location.reload();
                    } else {
                        alert("To create a user objective of type 'Division' you must select only one on the objective selection.");
                    }

                } else if (newUO.uoType == "Fusion") {
                    if (subObjectivesArray.length >= 2) {
                        var params = this.toFormData(newUO);
                        Axios.post('http://localhost:80/APICRUD/Projects/createUserObjectiveFusionRelation.php', params)
                        .then( function (response) {
                            console.log(response.data);
                            if (response.data != "ERROR") {
                                var uos = response.data.split("/");
                                var uosArray = [];
                                uosArray.splice(0, uosArray.length);
                                for (var i = 0; i < uos.length - 1; i++) {
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
                                userInfo.dispatch('updatingUserObjectives', uosArray);
                            } else {
                                alert("An error occured during the user objective type change.");
                            }
                        })
                        .catch( function (error) {
                            console.log(error);
                        });
                        window.location.reload();
                    } else {
                        alert("You need to select more user objectives to create one of type 'Fusion'.");
                    }

                } else if (newUO.uoType == "Incrementado") {
                    var isIncrement = 0;
                    for (var i = 0; i < subObjectivesArray.length; i++) {
                        for (var j = 0; j < this.userObjectives.length; j++) {
                            if (this.userObjectives[j].uoID == subObjectivesArray[i].split(" ")[1] && this.userObjectives[j].uoType == "Incremento") {
                                isIncrement++;
                            }
                        }
                    }
                    console.log(newUO);
                    if (isIncrement > 0) {
                        var params = this.toFormData(newUO);
                        Axios.post('http://localhost:80/APICRUD/Projects/createUserObjectiveFusionRelation.php', params)
                        .then( function (response) {
                            console.log(response.data);
                            if (response.data != "ERROR") {
                                var uos = response.data.split("/");
                                var uosArray = [];
                                uosArray.splice(0, uosArray.length);
                                for (var i = 0; i < uos.length - 1; i++) {
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
                                userInfo.dispatch('updatingUserObjectives', uosArray);
                            } else {
                                alert("An error occured during the user objective type change.");
                            }
                        })
                        .catch( function (error) {
                            console.log(error);
                        });
                        window.location.reload();
                    } else {
                        alert("There must be at least one user objective of type 'Incremento'. ");
                    }
                }
            },
            showUserObjective: function(d) {
                console.log(d);
                var uo = [];
                for (var i = 0; i < this.userObjectives.length; i++) {
                    if (this.userObjectives[i].uoID == d) {
                        uo = this.userObjectives[i];
                    }
                }
                // EDIT button-->
                if (this.project.archived == 0 && uo.reject == 0 && uo.inactive == 0) {
                    this.uoForm = true;
                } else {
                    this.uoForm = false;
                }
                // REJECT button-->
                if (this.project.archived == 0 && uo.reject == 0 && uo.inactive == 0 && uo.state == "Pool of ideas") {
                    this.uoRejected = true;
                } else {
                    this.uoRejected = false;
                }
                // DISABLE button-->
                if (this.project.archived == 0 && uo.inactive == 0 && uo.reject == 0) {
                    this.uoDisabled = true;
                } else {
                    this.uoDisabled = false;
                }
                // ENABLE button-->
                if (this.project.archived == 0 && uo.inactive == 1) {
                    this.uoEnabled = true;
                } else {
                    this.uoEnabled = false;
                }
                this.newUserObjective.uoId = uo.uoID;
                for (var i = 0; i < document.getElementById("uostate").length; i++) {
                    if (document.getElementById("uostate")[i].value == uo.state) {
                        document.getElementById("uostate").selectedIndex = i;
                        this.newUserObjective.uoState = document.getElementById("uostate")[i].value;
                    }
                }
                $('select').material_select();

                if (uo.uoType == "Division" || uo.uoType == "Fusion" || uo.uoType == "Incrementado") {
                    this.newUserObjective.uoType = uo.uoType + " (" + uo.uoAntecesor + ")";
                } else {
                    this.newUserObjective.uoType = uo.uoType;
                }
                //document.getElementById("uoname").value = uo.uoName;
                this.newUserObjective.uoName = uo.uoName;
                //document.getElementById("uopredecessor").value = "";
                this.newUserObjective.uoPredecessor = uo.uoAntecesor;
                //document.getElementById("uosucessor").value = uo.uoAntecesor;
                this.newUserObjective.uoSucessor = uo.uoSucesor;
                if (uo.description != "") {
                    //document.getElementById("uodescription").value = "No description";
                    this.newUserObjective.uoDescription = uo.description;
                }
                this.newUserObjective.uoInactive = uo.inactive;
                this.newUserObjective.uoReject = uo.reject;
                this.newUserObjective.time = uo.time;
            },
            editUserObjective: function () {
                if (this.project.archived == 0) {
                    if (document.getElementById("editUOBtn").text == "Edit") {
                        document.getElementById("timeLabel").textContent = "Time invested now (hours) :";
                        document.getElementById("time").value = "";
                        document.getElementById("editUOBtn").text = "Save";
                        for (var i = 0; i < document.getElementById("uostate").length - 1; i++) {
                            if (document.getElementById("uostate").selectedIndex == i) {
                                this.newUserObjective.uoState = document.getElementById("uostate")[i].value;
                                if (i + 1 < document.getElementById("uostate").length) {
                                    document.getElementById("uostate")[i + 1].disabled = false;
                                }
                                if (i - 1 >= 0) {
                                    document.getElementById("uostate")[i - 1].disabled = false;
                                }
                            }
                        }
                        $('select').material_select();
                        document.getElementById("uoname").disabled = false;
                        if (this.newUserObjective.uoState == "Pool of ideas") {
                            document.getElementById("uotype").disabled = false;
                        }
                        document.getElementById("uodescription").disabled = false;
                        document.getElementById("time").disabled = false;
                    } else {    // Saving new objective details on DB-->
                        var oldUO = [];
                        for (var i = 0; i < this.userObjectives.length; i++) {
                            if (this.userObjectives[i].uoID == this.newUserObjective.uoId) {
                                oldUO = this.userObjectives[i];
                            }
                        }

                        this.newUserObjective.uoState = document.getElementById("uostate")[document.getElementById("uostate").selectedIndex].value;
                        var newType = "";
                        var newPredecessorsSplitted = "";
                        console.log(this.newUserObjective.uoType);
                        if (this.newUserObjective.uoType != "Directo" && this.newUserObjective.uoType != "Reutilizado" && this.newUserObjective.uoType != "Incremento") {
                            newType = document.getElementById("uotype").value.split(" ")[0];
                            var newPredecessors = "";
                            newPredecessors = document.getElementById("uotype").value.split(" ")[1];
                            if (newPredecessors == undefined) {
                                alert("You need to specify the objectives between parenthesis, please");
                            } else {
                                newPredecessorsSplitted = newPredecessors.substring(newPredecessors.lastIndexOf("(") + 1, newPredecessors.lastIndexOf(")"));
                            }
                        } else {
                            newType = document.getElementById("uotype").value;
                        }
                        if (document.getElementById("time").value == "") {
                            this.newUserObjective.time = parseFloat(oldUO.time);
                        } else {
                            this.newUserObjective.time = parseFloat(oldUO.time) + parseFloat(document.getElementById("time").value);
                        }
                        if (oldUO.state == this.newUserObjective.uoState && oldUO.uoType == newType && oldUO.uoAntecesor == newPredecessorsSplitted && oldUO.uoName == this.newUserObjective.uoName && oldUO.description == this.newUserObjective.uoDescription && parseFloat(oldUO.time) == this.newUserObjective.time) {
                            for (var i = 0; i < document.getElementById("uostate").length; i++) {
                                document.getElementById("uostate")[i].disabled = true;
                            }
                            $('select').material_select();
                            document.getElementById("uoname").disabled = true;
                            document.getElementById("uotype").disabled = true;
                            document.getElementById("uodescription").disabled = true;
                            document.getElementById("timeLabel").textContent = "Total time invested (hours)";
                            document.getElementById("time").value = oldUO.time;
                            document.getElementById("editUOBtn").text = "Edit";
                            if (this.newUserObjective.uoType != "Directo" || this.newUserObjective.uoType != "Reutilizado" || this.newUserObjective.uoType != "Incremento") {
                                this.newUserObjective.uoType = document.getElementById("uotype").value;
                            }
                        /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

                                                                                        ************** USER OBJECTIVE TYPE CHANGE **************

                        -------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                        } else if (oldUO.state == "Pool of ideas" && (oldUO.uoType != newType || oldUO.uoAtecesor != newPredecessorsSplitted) && oldUO.uoSucesor == "") {
                            console.log("here");
                            if (newType == "Division" || newType == "Fusion") {
                                var newPre = document.getElementById("uotype").value.split(" ")[1];
                                var updatedPredecessor = newPre.substring(newPre.lastIndexOf("(") + 1, newPre.lastIndexOf(")"));
                                var newUO = {
                                    username: this.currentUser,
                                    pName: this.project.name,
                                    uoID: this.newUserObjective.uoId,
                                    uoName: this.newUserObjective.uoName,
                                    state: this.newUserObjective.uoState,
                                    description: this.newUserObjective.uoDescription,
                                    type: newType,
                                    oldUOPredecessor: oldUO.uoAntecesor,
                                    newUOPredecessor: updatedPredecessor,
                                    time: this.newUserObjective.time
                                };
                                var params = this.toFormData(newUO);
                                Axios.post('http://localhost:80/APICRUD/Projects/changeUOTypeWithRelation.php', params)
                                .then( function (response) {
                                    console.log(response.data);
                                    if (response.data != "ERROR") {
                                        var uos = response.data.split("/");
                                        var uosArray = [];
                                        uosArray.splice(0, uosArray.length);
                                        for (var i = 0; i < uos.length - 1; i++) {
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
                                        userInfo.dispatch('updatingUserObjectives', uosArray);
                                    } else {
                                        alert("An error occured during the user objective type change.");
                                    }

                                })
                                .catch( function (error) {
                                    console.log(error);
                                });
                                window.location.reload();
                            } else if (newType == "Incrementado") {
                                var isIncrement = 0;
                                var newPre = document.getElementById("uotype").value.split(" ")[1];
                                var predecessor = newPre.substring(newPre.lastIndexOf("(") + 1, newPre.lastIndexOf(")"));
                                var splittedPredecessor = predecessor.split(",");
                                for (var i = 0; i < splittedPredecessor.length; i++) {
                                    for (var j = 0; j < this.userObjectives.length; j++) {
                                        if (splittedPredecessor[i] == this.userObjectives[j].uoID && this.userObjectives[j].uoType == "Incremento") {
                                            isIncrement++;
                                        }
                                    }
                                }
                                if (isIncrement > 0) {
                                    var newUO = {
                                        username: this.currentUser,
                                        pName: this.project.name,
                                        uoID: this.newUserObjective.uoId,
                                        uoName: this.newUserObjective.uoName,
                                        state: this.newUserObjective.uoState,
                                        description: this.newUserObjective.uoDescription,
                                        type: newType,
                                        oldUOPredecessor: oldUO.uoAntecesor,
                                        newUOPredecessor: predecessor,
                                        time: this.newUserObjective.time
                                    };
                                    var params = this.toFormData(newUO);
                                    Axios.post('http://localhost:80/APICRUD/Projects/changeUOTypeWithRelation.php', params)
                                    .then( function (response) {
                                        console.log(response.data);
                                        if (response.data != "ERROR") {
                                            var uos = response.data.split("/");
                                            var uosArray = [];
                                            uosArray.splice(0, uosArray.length);
                                            for (var i = 0; i < uos.length - 1; i++) {
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
                                            userInfo.dispatch('updatingUserObjectives', uosArray);
                                        } else {
                                            alert("An error occured during the user objective type change.");
                                        }

                                    })
                                    .catch( function (error) {
                                        console.log(error);
                                    });
                                } else {
                                    alert("You need at least one user objective of type 'Incremento' to change to type 'Incrementado'.");
                                }
                                window.location.reload();
                            } else {
                                var newUO = {
                                    username: this.currentUser,
                                    pName: this.project.name,
                                    uoID: this.newUserObjective.uoId,
                                    uoName: this.newUserObjective.uoName,
                                    state: this.newUserObjective.uoState,
                                    description: this.newUserObjective.uoDescription,
                                    type: newType,
                                    uoPredecessor: oldUO.uoAntecesor,
                                    time: this.newUserObjective.time
                                };
                                console.log(newUO);
                                var params = this.toFormData(newUO);
                                Axios.post('http://localhost:80/APICRUD/Projects/changeUOTypeWithoutRelation.php', params)
                                .then( function (response) {
                                    if (response.data != "ERROR") {
                                        var uos = response.data.split("/");
                                        var uosArray = [];
                                        uosArray.splice(0, uosArray.length);
                                        for (var i = 0; i < uos.length - 1; i++) {
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
                                        userInfo.dispatch('updatingUserObjectives', uosArray);
                                    } else {
                                        alert("An error occured during the user objective type change.");
                                    }
                                })
                                .catch( function (error) {
                                    console.log(error);
                                });
                                window.location.reload();
                            }
                        /*-----------------------------------------------------------------------------------------------------------------------------------------------------------------------

                                                                                        ************** USER OBJECTIVE INFO CHANGE **************

                        -------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
                        } else {
                            var newUO = {
                                username: this.currentUser,
                                pName: this.project.name,
                                uoID: this.newUserObjective.uoId,
                                state: this.newUserObjective.uoState,
                                uoName: this.newUserObjective.uoName,
                                description: this.newUserObjective.uoDescription,
                                time: this.newUserObjective.time
                            };
                            var params = this.toFormData(newUO);
                            if (oldUO.uoType != "Fusion" && oldUO.uoType != "Incrementado") {
                                Axios.post('http://localhost:80/APICRUD/Projects/updateUserObjective.php', params)
                                .then( function (response) {
                                    if (response.data != "ERROR") {
                                        var uos = response.data.split("/");
                                        var uosArray = [];
                                        uosArray.splice(0, uosArray.length);
                                        for (var i = 0; i < uos.length - 1; i++) {
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
                                        userInfo.dispatch('updatingUserObjectives', uosArray);
                                    } else {
                                        alert("An error occured during the user objective type change.");
                                    }
                                })
                                .catch( function (error) {
                                    alert("There was a problem editing the user objective.");
                                });
                                window.location.reload();
                            } else {
                                var newUO = {
                                    username: this.currentUser,
                                    pName: this.project.name,
                                    uoID: this.newUserObjective.uoId,
                                    state: this.newUserObjective.uoState,
                                    uoName: this.newUserObjective.uoName,
                                    description: this.newUserObjective.uoDescription,
                                    time: this.newUserObjective.time
                                };
                                var params = this.toFormData(newUO);
                                Axios.post('http://localhost:80/APICRUD/Projects/updateFusionIncrementedUserObjective.php', params)
                                .then( function (response) {
                                    if (response.data != "ERROR") {
                                        var uos = response.data.split("/");
                                        var uosArray = [];
                                        uosArray.splice(0, uosArray.length);
                                        for (var i = 0; i < uos.length - 1; i++) {
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
                                        userInfo.dispatch('updatingUserObjectives', uosArray);
                                    } else {
                                        alert("An error occured during the user objective type change.");
                                    }
                                })
                                .catch( function (error) {
                                    alert("There was a problem editing the user objective.");
                                });
                                window.location.reload();
                            }
                        }
                    }
                } else {
                    this.uoForm = false;
                }

            },
            disableUserObjective: function() {
                if (this.project.archived == 1) {
                    alert("Can not disable an objetive in an archived project.");
                } else if (this.newUserObjective.uoReject == 1) {
                    alert("Can not disable a rejected objective.");
                } else if (this.newUserObjective.uoInactive == 0) {
                    // Change inactive = 1; on DB too
                    for (var i = 0; i < this.userObjectives.length; i++) {
                        if (this.userObjectives[i].uoID == this.newUserObjective.uoId) {
                            this.userObjectives[i].inactive = 1;
                        }
                    }
                    userInfo.dispatch('updatingUserObjectives', this.userObjectives);
                    var inactiveUO = {
                        username: this.currentUser,
                        pName: this.project.name,
                        uoID: this.newUserObjective.uoId
                    };
                    var params = this.toFormData(inactiveUO);
                    Axios.post('http://localhost:80/APICRUD/Projects/disableUserObjective.php', params)
                    .then( function (response) {
                        console.log(response.data);
                    })
                    .catch( function (error) {
                        console.log(error);
                    });
                    window.location.reload();
                }
            },
            enableUserObjective: function() {
                if (this.project.archived == 1) {
                    alert("Can not enable an objetive in an archived project.");
                } else if (this.newUserObjective.uoReject == 1) {
                    alert("Can not enable a rejected objective.");
                } else if (this.newUserObjective.uoInactive == 1) {
                    // Change inactive = 0; on DB too
                    for (var i = 0; i < this.userObjectives.length; i++) {
                        if (this.userObjectives[i].uoID == this.newUserObjective.uoId) {
                            this.userObjectives[i].inactive = 0;
                        }
                    }
                    userInfo.dispatch('updatingUserObjectives', this.userObjectives);
                    var activeUO = {
                        username: this.currentUser,
                        pName: this.project.name,
                        uoID: this.newUserObjective.uoId
                    };
                    var params = this.toFormData(activeUO);
                    Axios.post('http://localhost:80/APICRUD/Projects/enableUserObjective.php', params)
                    .then( function (response) {
                        console.log(response.data);
                    })
                    .catch( function (error) {
                        console.log(error);
                    });
                    window.location.reload();
                }
            },
            rejectUserObjective: function() {
                if (this.project.archived == 1) {
                    this.uoForm = false;
                } else if (this.project.archived == 0 && this.newUserObjective.uoState == "Pool of ideas" && this.newUserObjective.uoSucessor != "") {
                    alert("The user objective has objective dependencies. Can not reject it.");
                } else if (this.newUserObjective.uoState != "Pool of ideas") {
                    alert("The user objective is already in one activity. Can not reject it");
                } else if (this.newUserObjective.uoSucessor != "") {
                    alert("The user objective has objective dependencies. Can not reject it.");
                } else {
                    // Change reject = 1; on DB too
                    for (var i = 0; i < this.userObjectives.length; i++) {
                        if (this.userObjectives[i].uoID == this.newUserObjective.uoId) {
                            this.userObjectives[i].reject = 1;
                        }
                    }
                    userInfo.dispatch('updatingUserObjectives', this.userObjectives);
                    var rejectedUO = {
                        username: this.currentUser,
                        pName: this.project.name,
                        uoID: this.newUserObjective.uoId
                    };
                    var params = this.toFormData(rejectedUO);
                    Axios.post('http://localhost:80/APICRUD/Projects/rejectUserObjective.php', params)
                    .then( function (response) {
                        console.log(response.data);
                    })
                    .catch( function(error) {
                        console.log(error);
                    })
                    window.location.reload();
                }
            },
            toggleForm: function() {
                if (document.getElementById("editButton").text == "Edit") {
                    document.getElementById("editButton").text = "Save";
                } else if (this.newProject.name != "" || this.newProject.description != "") {
                    var saveProjectName = (this.newProject.name != "") ? this.newProject.name : this.project.name;
                    var saveProjectDescription = (this.newProject.description != "") ? this.newProject.description : this.project.description;
                    var saveProject = { username: this.currentUser, oldName: this.project.name, oldDescription: this.project.description, newName: saveProjectName, newDescription: saveProjectDescription};
                    var params = this.toFormData(saveProject);
                    var self = this;
                    Axios.post('http://localhost:80/APICRUD/Projects/editProject.php', params)
                    .then(function (response) {
                        console.log(response.data);
                        if (response.data == "PROJECTACTIVE") {
                            alert("There is already a project with the same name.");
                        }else if (response.data == "ERROR") {
                            alert("There was an error updating the project. Try again later.")
                        } else if (response.data != "") {
                            var changeProject = [self.project.name, response.data.split("¿")[0], response.data.split("¿")[1]];
                            userInfo.dispatch('changingProjectInfo', changeProject);
                            window.location.href = "/";
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });

                    document.getElementById("editButton").text = "Edit";
                } else {
                    document.getElementById("editButton").text = "Edit";
                }
                this.projectForm = !this.projectForm;
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
    #projectGraph {
        margin-left: 0px;
    }
    #project-canvas {
        display: inline-block;
        position: relative;
        //width: 75%;
        //height: 50%;
        //max-width: 960px;
        //max-height: 550px;
        overflow: auto;
        white-space: nowrap;
    }
    #projectDB {
        display: inline-block;
        min-width: 300px;
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
    #projectMenu {
        position:fixed;
        display: block;
        width: 15%;
        height: 100%;
        top: 64px;
        background: teal;
    }
    #modalNewUserObjective {
        width: 600px;
        overflow: hidden;
    }
    #modalClose {
        margin-top: 20px;
        margin-right: 30px;
    }
    #userObjectiveForm {
        margin-top: 55px;
        margin-left: 20px;
        margin-right: 20px;
    }
    .dropdown-content {
        max-height: 265px;
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
    .custom {
        font-size: 16px;
        font-style: oblique;
        font-weight: bold;
    }
    </style>
