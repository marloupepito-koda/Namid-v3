<template>
    <div>
      <h1>Tasks</h1>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(task, index) in tasks" :key="index">
            <td>{{ task.title }}</td>
            <td>{{ task.description }}</td>
            <td>
              <button @click="editTask(index)">Edit</button>
              <button @click="deleteTask(index)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <form @submit.prevent="submitForm">
        <label>
          Title:
          <input type="text" v-model="form.title">
        </label>
        <label>
          Description:
          <input type="text" v-model="form.description">
        </label>
        <button type="submit">{{ editMode ? 'Update' : 'Create' }}</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        tasks: [],
        form: {
          title: '',
          description: ''
        },
        editMode: false,
        editedTaskIndex: null
      }
    },
    mounted() {
      this.loadTasks()
    },
    methods: {
      loadTasks() {
        const tasks = JSON.parse(localStorage.getItem('tasks'))
        if (tasks) {
          this.tasks = tasks
        }
      },
      saveTasks() {
        localStorage.setItem('tasks', JSON.stringify(this.tasks))
      },
      submitForm() {
        if (this.editMode) {
          this.tasks[this.editedTaskIndex].title = this.form.title
          this.tasks[this.editedTaskIndex].description = this.form.description
          this.editMode = false
          this.editedTaskIndex = null
        } else {
          const task = {
            title: this.form.title,
            description: this.form.description
          }
          this.tasks.push(task)
        }
        this.form.title = ''
        this.form.description = ''
        this.saveTasks()
      },
      editTask(index) {
        this.form.title = this.tasks[index].title
        this.form.description = this.tasks[index].description
        this.editMode = true
        this.editedTaskIndex = index
      },
      deleteTask(index) {
        this.tasks.splice(index, 1)
        this.saveTasks()
      }
    }
  }
  </script>
  
