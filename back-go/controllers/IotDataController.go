package controllers

import (
	"github.com/eagle-fox/dawMp/models"
	"github.com/gin-gonic/gin"
	"net/http"
	"strconv"
)

func IotDataControllerIndex(c *gin.Context) {
	var data []models.IoTData

	if err := models.DB.Limit(1024).Find(&data).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error getting data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": data})
}

func IotDataControllerStore(c *gin.Context) {
	var input struct {
		DeviceID  string `json:"device"`
		Latitude  string `json:"latitude"`
		Longitude string `json:"longitude"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	deviceID, err := strconv.Atoi(input.DeviceID)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid device ID"})
		return
	}

	latitude, err := strconv.ParseFloat(input.Latitude, 64)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid latitude"})
		return
	}

	longitude, err := strconv.ParseFloat(input.Longitude, 64)
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid longitude"})
		return
	}

	data := models.IoTData{
		Device:    deviceID,
		Latitude:  latitude,
		Longitude: longitude,
	}

	if err := models.DB.Create(&data).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error creating data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": data})
}

func IotDataControllerShow(c *gin.Context) {
	var data models.IoTData
	id := c.Param("id")

	if err := models.DB.First(&data, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": data})
}

func IotDataControllerUpdate(c *gin.Context) {
	var data models.IoTData
	id := c.Param("id")

	if err := models.DB.First(&data, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
		return
	}

	if err := c.ShouldBindJSON(&data); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	models.DB.Save(&data)
	c.JSON(http.StatusOK, gin.H{"data": data})
}

func IotDataControllerDestroy(c *gin.Context) {
	var data models.IoTData
	id := c.Param("id")

	if err := models.DB.First(&data, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
		return
	}

	models.DB.Delete(&data)
	c.JSON(http.StatusOK, gin.H{"data": true})
}
