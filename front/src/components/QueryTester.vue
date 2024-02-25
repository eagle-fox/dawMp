<template>

</template>

<script setup>

import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import User from '@/types/User.js'

let myURL = new URL('http', 'localhost', 2003)
let myBasicAuth = new BasicAuth('admin@admin.com', 'admin')
let myQuery = new Query(myURL).withAuth(myBasicAuth)
let data = await myQuery.getUsers()
console.log(data)

let nuevoUsuario = new User()
nuevoUsuario.withNombre('Juan')
    .withApellidoPrimero('Perez')
    .withApellidoSegundo('Gomez')
    .withEmail('pepe@juanperez.com')
    .withPassword('1234')
    .withRol('user')
    .build()
console.log(nuevoUsuario)

</script>

<style scoped>
/* Estilos aquí */
</style>