package controllers

import (
	"back-go/models"
	"errors"
	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
	"log"
	"net/http"
	"strings"
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
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "*")
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
			c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
			c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
			c.Writer.Header().Set("Access-Control-Allow-Methods", "*")
			c.JSON(http.StatusNotFound, gin.H{"error": "Device not found"})
		} else {
			c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
			c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
			c.Writer.Header().Set("Access-Control-Allow-Methods", "*")
			c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		}
		return
	}

	result = models.DB.Delete(&device)
	if result.Error != nil {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "*")
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, gin.H{"message": "Device deleted"})
}

// GetIotDevicesByMyself returns the IoT devices for the authenticated user.
func GetIotDevicesByMyself(c *gin.Context) {
	// Get the bearer token from the request header
	authHeader := c.GetHeader("Authorization")
	if authHeader == "" {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "*")
		log.Println("No Authorization header provided")
		c.JSON(http.StatusUnauthorized, gin.H{"error": "No Authorization header provided"})
		return
	}

	// Extract the token from the Authorization header
	token := strings.TrimPrefix(authHeader, "Bearer ")

	// Get the user by the bearer token
	user, err := getUserByBearerToken(token)
	if err != nil {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "*")
		log.Println("Invalid token")
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Invalid token"})
		return
	}

	// Get the IoT devices for the user
	var devices []models.IotDevice
	result := models.DB.Where("user = ?", user.ID).Find(&devices)

	if result.Error != nil {
		c.Writer.Header().Set("Content-Type", "application/json")
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		log.Println(result.Error.Error())
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}
	log.Println(devices)
	c.Writer.Header().Set("Access-Control-Allow-Origin", "*")

	c.JSON(http.StatusOK, devices)
}
