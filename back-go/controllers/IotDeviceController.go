package controllers

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

func IotDeviceControllerIndex(c *gin.Context) {
	// Aquí va tu código para manejar la solicitud
	c.JSON(http.StatusOK, gin.H{"message": "index"})
}

func IotDeviceControllerStore(c *gin.Context) {
	// Aquí va tu código para manejar la solicitud
	c.JSON(http.StatusOK, gin.H{"message": "store"})
}

func IotDeviceControllerShow(c *gin.Context) {
	// Aquí va tu código para manejar la solicitud
	c.JSON(http.StatusOK, gin.H{"message": "show"})
}

func IotDeviceControllerUpdate(c *gin.Context) {
	// Aquí va tu código para manejar la solicitud
	c.JSON(http.StatusOK, gin.H{"message": "update"})
}

func IotDeviceControllerDestroy(c *gin.Context) {
	// Aquí va tu código para manejar la solicitud
	c.JSON(http.StatusOK, gin.H{"message": "destroy"})
}
