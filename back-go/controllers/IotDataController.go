package controllers

import (
	"github.com/eagle-fox/dawMp/models"
	"github.com/gin-gonic/gin"
	"net/http"
)

func IotDataControllerIndex(c *gin.Context) {
	var data []models.IoTData

	if err := models.DB.Find(&data).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error getting data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": data})
}

// // IotDataControllerStore maneja la solicitud para crear un nuevo dato de un dispositivo IoT.
// func IotDataControllerStore(c *gin.Context) {
// 	var inputData struct {
// 		Device    string  `json:"device" binding:"required"`
// 		Latitude  float64 `json:"latitude" binding:"required"`
// 		Longitude float64 `json:"longitude" binding:"required"`
// 	}
//
// 	if err := c.ShouldBindJSON(&inputData); err != nil {
// 		c.JSON(http.StatusBadRequest, gin.H{"message": "Invalid data format"})
// 		return
// 	}
//
// 	newData := models.IotData{
// 		Device:    inputData.Device,
// 		Latitude:  inputData.Latitude,
// 		Longitude: inputData.Longitude,
// 	}
//
// 	if err := models.DB.Create(&newData).Error; err != nil {
// 		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error creating data"})
// 		return
// 	}
//
// 	c.JSON(http.StatusOK, gin.H{"message": "Data created", "data": newData})
// }
//
// // IotDataControllerShow maneja la solicitud para obtener un dato específico de un dispositivo IoT.
// func IotDataControllerShow(c *gin.Context) {
// 	id, err := strconv.Atoi(c.Param("id"))
// 	if err != nil {
// 		c.JSON(http.StatusBadRequest, gin.H{"message": "Invalid ID"})
// 		return
// 	}
//
// 	var data models.IotData
// 	if err := models.DB.First(&data, id).Error; err != nil {
// 		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
// 		return
// 	}
//
// 	c.JSON(http.StatusOK, gin.H{"message": "Data found", "data": data})
// }
//
// // IotDataControllerUpdate maneja la solicitud para actualizar un dato de un dispositivo IoT (aunque no está permitido, devuelve un error 405).
// func IotDataControllerUpdate(c *gin.Context) {
// 	c.JSON(http.StatusMethodNotAllowed, gin.H{"message": "Method not allowed"})
// }
//
// // IotDataControllerDestroy maneja la solicitud para eliminar un dato específico de un dispositivo IoT.
// func IotDataControllerDestroy(c *gin.Context) {
// 	id, err := strconv.Atoi(c.Param("id"))
// 	if err != nil {
// 		c.JSON(http.StatusBadRequest, gin.H{"message": "Invalid ID"})
// 		return
// 	}
//
// 	var data models.IotData
// 	if err := models.DB.First(&data, id).Error; err != nil {
// 		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
// 		return
// 	}
//
// 	if err := models.DB.Delete(&data).Error; err != nil {
// 		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error deleting data"})
// 		return
// 	}
//
// 	c.JSON(http.StatusOK, gin.H{"message": "Data deleted"})
// }
//
// // getUserFromContext es una función de utilidad para obtener el usuario del contexto de Gin.
// func getUserFromContext(c *gin.Context) *types.User {
// 	user, _ := c.Get("user")
// 	if u, ok := user.(*types.User); ok {
// 		return u
// 	}
// 	return nil
// }
