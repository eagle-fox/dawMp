<template>

</template>

<script setup>

import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'
import User from '@/types/User.js'
import BearerToken from '@/types/BearerToken.js'

/**
 * Ejemplo de uso de la clase Query para obtener usuarios desde la base de datos
 */
let myURL = new URL('http', 'localhost', 2003)
let myBasicAuth = new BasicAuth('admin@admin.com', 'admin')
let bearerTokenAuth = new BearerToken()


let myQuery = new Query(myURL).withAuth(myBasicAuth)
let data = await myQuery.getUsers()
console.log(data)

/**
 * Ejemplo de uso de la clase User para crear un nuevo usuario en local antes de POST
 */
let nuevoUsuario = new User()
nuevoUsuario.withNombre('Juan')
    .withApellidoPrimero('Perez')
    .withApellidoSegundo('Gomez')
    .withEmail('pepe@juanperez.com')
    .withPassword('1234')
    .withRol('user')
    .build()
console.log(nuevoUsuario)
console.log(bearerTokenAuth)
</script>

<style scoped>
/* Estilos aquí */
</style>