package controllers

import (
	"back-go/models"
	"errors"
	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
	"net/http"
)

// IotDeviceControllerIndex handles the GET request to fetch all IoT devices.
// It returns a list of IoT device records in JSON format.
func IotDeviceControllerIndex(c *gin.Context) {
	var devices []models.IotDevice
	result := models.DB.Find(&devices)

	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, devices)
}

// IotDeviceControllerStore handles the POST request to create a new IoT device.
// It expects JSON input with device details.
func IotDeviceControllerStore(c *gin.Context) {
	var device models.IotDevice
	if err := c.ShouldBindJSON(&device); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	result := models.DB.Create(&device)
	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, device)
}

// IotDeviceControllerShow handles the GET request to fetch a specific IoT device by ID.
// It returns the IoT device record in JSON format.
func IotDeviceControllerShow(c *gin.Context) {
	var device models.IotDevice
	result := models.DB.First(&device, c.Param("id"))

	if result.Error != nil {
		if errors.Is(result.Error, gorm.ErrRecordNotFound) {
			c.JSON(http.StatusNotFound, gin.H{"error": "Device not found"})
		} else {
			c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		}
		return
	}

	c.JSON(http.StatusOK, device)
}

// IotDeviceControllerUpdate handles the PUT request to update a specific IoT device by ID.
// It expects JSON input with device details.
func IotDeviceControllerUpdate(c *gin.Context) {
	var device models.IotDevice
	result := models.DB.First(&device, c.Param("id"))

	if result.Error != nil {
		if errors.Is(result.Error, gorm.ErrRecordNotFound) {
			c.JSON(http.StatusNotFound, gin.H{"error": "Device not found"})
		} else {
			c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		}
		return
	}

	if err := c.ShouldBindJSON(&device); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	result = models.DB.Save(&device)
	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, device)
}

// IotDeviceControllerDestroy handles the DELETE request to delete a specific IoT device by ID.
// It returns a confirmation message in JSON format.
func IotDeviceControllerDestroy(c *gin.Context) {
	var device models.IotDevice
	result := models.DB.First(&device, c.Param("id"))

	if result.Error != nil {
		if errors.Is(result.Error, gorm.ErrRecordNotFound) {
			c.JSON(http.StatusNotFound, gin.H{"error": "Device not found"})
		} else {
			c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		}
		return
	}

	result = models.DB.Delete(&device)
	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Device deleted"})
}
