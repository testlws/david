<template>
    <v-container style="max-width: 500px">
        <v-text-field v-model="newTask" label="Enter new task" solo @keydown.enter="create" :loading="addingTask"
            :disabled="addingTask">
            <template v-slot:append>
                <v-fade-transition>
                    <v-icon v-if="newTask" @click="create">
                        mdi-plus
                    </v-icon>
                </v-fade-transition>
            </template>
        </v-text-field>

        <h2 class="display-1 success--text pl-0" style="color:#1797c2 !important;" v-if="tasks">
            Tasks:&nbsp;
            <v-fade-transition leave-absolute>
                <span :key="`tasks-${tasks.length}`">
                    {{ tasks.length }}
                </span>
            </v-fade-transition>
        </h2>

        <v-divider class="mt-4"></v-divider>

        <v-row class="my-1" align="center">

            <strong class="mx-4 info--text text--darken-2">
                Remaining: {{ remainingTasks }}
            </strong>

            <v-divider vertical></v-divider>

            <strong class="mx-4 success--text text--darken-2">
                Completed: {{ completedTasks }}
            </strong>

            <v-spacer></v-spacer>

            <v-progress-circular color="lime" :value="progress" class="mr-2"></v-progress-circular>
        </v-row>

        <v-divider class="mb-4"></v-divider>
            <v-radio-group
      v-model="viewName"
      row
      dark
    >
      <v-radio
        value="all"
      >
      <template v-slot:label>
          <div class="mr-4" style="color: rgb(23, 151, 194);">All</div>
      </template>
      </v-radio>
      <v-radio
        value="open"
      >
            <template v-slot:label>
          <div class="mr-4" style="color: #00791e;">Open</div>
      </template>
      </v-radio>
      <v-radio
        value="closed"
      >
            <template v-slot:label>
          <div class="mr-4" style="color: red;">Closed</div>
      </template>
      </v-radio>
    </v-radio-group>

        <v-card v-if="tasks.length > 0">
            <v-slide-y-transition class="py-0" group tag="v-list">
                <template v-for="(task, i) in filteredTasks">
                    <v-divider v-if="i !== 0" :key="`${i}-divider`"></v-divider>

                    <v-list-item :key="`${i}-${task.name}`">
                        <v-list-item-action>
                            <v-checkbox v-model="task.done" @click="changeStatus(task.id)"
                                :color="task.done && 'grey' || 'primary'">
                                <template v-slot:prepend>
                                    <v-icon color="error" @click="deleteTask(task.id)">
                                        mdi-delete-circle
                                    </v-icon>
                                </template>
                                <template v-slot:label>
                                    <div :class="task.done && 'grey--text' || 'primary--text'" class="ml-4"
                                        >{{ task.name }} <span v-if="task.end_date" class="caption">({{ task.end_date | formatDate }})</span></div>
                                </template>
                            </v-checkbox>
                        </v-list-item-action>

                        <v-spacer></v-spacer>

                        <v-scroll-x-transition>
                            <v-icon v-if="task.done" color="success">
                                mdi-check
                            </v-icon>
                        </v-scroll-x-transition>
                    </v-list-item>
                </template>
            </v-slide-y-transition>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        props: [
            'id'
        ],
        data() {
            return {
                newTask: null,
                addingTask: false,
                viewName: 'all',
            }
        },
        computed: {
            filteredTasks: function () {
                if (this.viewName == 'all') return this.tasks;

                if (this.viewName == 'open') {
                    return this.tasks.filter(task => !task.done);
                } else {
                    return this.tasks.filter(task => task.done);
                }

            },
            tasks: function () {
                var todos = this.$store.getters.todos;
                return todos;
            },
            snackbar: function () {
                return this.$store.getters.snackbar;
            },
            todosLoading: function () {
                return this.$store.getters.todosLoading;
            },
            currentRoutePath: function () {
                return this.$route.path;
            },
            completedTasks() {
                return this.tasks.filter(task => task.done).length;
            },
            progress() {
                if (this.tasks.length == 0) return 0;
                return this.completedTasks / this.tasks.length * 100;
            },
            remainingTasks() {
                return this.tasks.length - this.completedTasks;
            },
        },
        methods: {
            deleteTask: function (taskId) {
                var index = this.$_.findIndex(this.tasks, {
                    id: taskId
                });
                if (index == -1) return;

                var task = this.tasks[index];

                this.axios
                    .delete('todos/delete/' + task.id)
                    .then(response => {
                        this.tasks.splice(index, 1);                    
                    })
                    .catch(error => console.log(error))
                    .finally(() => {});

            },
            changeStatus: function (taskId) {
                var index = this.$_.findIndex(this.tasks, {
                    id: taskId
                });
                if (index == -1) return;

                var task = this.tasks[index];
                this.axios
                    .post('todos/update/' + task.id, {
                        name: task.name,
                        done: task.done
                    })
                    .then(response => {
                        this.$set(this.tasks[index], 'id', response.data.id);
                        this.$set(this.tasks[index], 'name', response.data.name);
                        this.$set(this.tasks[index], 'done', response.data.done);
                        this.$set(this.tasks[index], 'end_date', response.data.end_date);
                    })
                    .catch(error => console.log(error))
                    .finally(() => {});
            },
            create() {
                if (this.addingTask || !this.newTask) return;
                this.addingTask = true;
                this.axios
                    .post('todos/create', {
                        name: this.newTask
                    })
                    .then(response => {
                        this.tasks.push({
                            id: response.data.id,
                            name: this.newTask,
                            done: 0,
                        });

                        this.newTask = null;
                    })
                    .catch(error => console.log(error))
                    .finally(() => {
                        this.addingTask = false;
                    });
            },
        },
        mounted() {
            this.$store.dispatch('LOAD_TODOS');
        },
        watch: {},
        components: {}
    }

</script>
