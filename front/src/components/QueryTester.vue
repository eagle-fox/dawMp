<template>

</template>

<script setup>

import Query from '@/types/Query.js'
import URL from '@/types/URL.js'
import BasicAuth from '@/types/BasicAuth.js'

let myURL = new URL('http', 'localhost', 2003)
let myBasicAuth = new BasicAuth('admin@admin.com', 'admin')
let myQuery = new Query(myURL).withAuth(myBasicAuth)
let data = await myQuery.getUsers()
console.log(data)

</script>

<style scoped>
/* Estilos aquí */
</style>