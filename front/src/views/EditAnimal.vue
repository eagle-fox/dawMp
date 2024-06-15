<template>
    <div class="animal-edit-page">
      <navbar></navbar>
      <div class="content">
        <div class="form-container">
          <h2>{{ $t('animaloptions.edit') }}</h2>
          <form @submit.prevent="submitForm">
            <div class="form-group">
              <label for="petName">{{ $t('animaloptions.name') }}</label>
              <input type="text" v-model="formData.petName" id="petName" required>
            </div>
            <div class="form-group">
              <label for="species">{{ $t('animaloptions.specie') }}</label>
              <select v-model="formData.species" id="species" required>
                <option value="dog">{{ $t('animaloptions.dog') }}</option>
                <option value="cat">{{ $t('animaloptions.cat') }}</option>
                <option value="pig">{{ $t('animaloptions.pig') }}</option>
                <option value="cow">{{ $t('animaloptions.cow') }}</option>
                <option value="sheep">{{ $t('animaloptions.sheep') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="birthDate">{{ $t('animaloptions.fecha_nacimiento') }}</label>
              <input type="date" v-model="formData.birthDate" id="birthDate" required>
            </div>
            <div class="form-actions">
              <button type="submit">{{ $t('animaloptions.save') }}</button>
              <button type="button" @click="cancelEdit">{{ $t('animaloptions.cancel') }}</button>
            </div>
          </form>
        </div>
      </div>
      <footermain></footermain>
    </div>
  </template>
  
  <script>
  import footermain from '@/components/FooterMain.vue';
  import navbar from '@/components/NavBar.vue';
  
  export default {
    name: 'AnimalEdit',
    components: {
      navbar,
      footermain
    },
    data() {
      return {
        formData: {
          petName: '',
          species: 'dog',
          birthDate: ''
        }
      };
    },
    methods: {
      submitForm() {
        console.log('Datos del animal:', this.formData);
      },
      cancelEdit() {
        console.log('Edición cancelada');
      }
    }
  };
  </script>
  
  <style scoped>
  .animal-edit-page {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #f9f9f9;
  }
  
  .content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-top: 2rem;
    padding-bottom: 2rem;
  }
  
  .form-container {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
  }
  
  .form-group {
    margin-bottom: 1rem;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
  }
  
  .form-group input,
  .form-group select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  .form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
  }
  
  .form-actions button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    flex: 1;
    margin: 0 0.5rem;
  }
  
  .form-actions button[type="submit"] {
    background-color: #28a745;
    color: white;
  }
  
  .form-actions button[type="button"] {
    background-color: #dc3545;
    color: white;
  }
  
  /* Media queries for responsiveness */
  @media (max-width: 600px) {
    .form-container {
      padding: 1rem;
    }
  
    .form-actions {
      flex-direction: column;
    }
  
    .form-actions button {
      margin: 0.5rem 0;
    }
  }
  </style>
  