// Package controllers provides the handlers for the HTTP endpoints of the application.
package controllers

import (
	"back-go/models"
	"github.com/gin-gonic/gin"
	"net/http"
	"strconv"
)

// IotDataControllerIndex handles the GET request to fetch all IoT data.
// It returns a list of IoT data records in JSON format.
// @param c *gin.Context - The context of the request.
func IotDataControllerIndex(c *gin.Context) {
	var data []models.IotData

	if err := models.DB.Limit(1024).Find(&data).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error getting data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": data})
}

// IotDataControllerStore handles the POST request to create a new IoT data record.
// It expects JSON input with device ID, latitude, and longitude.
// @param c *gin.Context - The context of the request.
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

	data := models.IotData{
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

// IotDataControllerShow handles the GET request to fetch a specific IoT data record by ID.
// It returns the IoT data record in JSON format.
// @param c *gin.Context - The context of the request.
func IotDataControllerShow(c *gin.Context) {
	var data models.IotData
	id := c.Param("id")

	if err := models.DB.First(&data, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": data})
}

// IotDataControllerUpdate handles the PUT request to update a specific IoT data record by ID.
// It expects JSON input to update the record.
// @param c *gin.Context - The context of the request.
func IotDataControllerUpdate(c *gin.Context) {
	var data models.IotData
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

// IotDataControllerDestroy handles the DELETE request to delete a specific IoT data record by ID.
// It returns a confirmation message in JSON format.
// @param c *gin.Context - The context of the request.
func IotDataControllerDestroy(c *gin.Context) {
	var data models.IotData
	id := c.Param("id")

	if err := models.DB.First(&data, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"message": "Data not found"})
		return
	}

	models.DB.Delete(&data)
	c.JSON(http.StatusOK, gin.H{"data": true})
}
