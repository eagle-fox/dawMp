// Package router sets up the routes for the web server using the Gin framework.
package router

import (
	"back-go/controllers"
	"back-go/middlewares"
	"github.com/gin-gonic/gin"
)

// SetupRouter configures the Gin engine with routes, middleware, and templates.
// It returns the configured *gin.Engine.
//
// The routes are organized into groups based on functionality:
// - IoT Data: CRUD operations for IoT data, protected by authentication middleware.
// - IoT Device: CRUD operations for IoT devices, protected by authentication middleware.
// - Users: User management including login, protected by authentication middleware.
//
// The root route serves a simple HTML page with API information.
func SetupRouter() *gin.Engine {
	// Initialize a Gin router with default middleware (logger and recovery)
	router := gin.Default()

	// Load HTML templates from the "templates" directory
	router.LoadHTMLGlob("templates/*")

	// Define the root route to serve an HTML page
	router.GET("/", func(c *gin.Context) {
		c.HTML(200, "index.html", gin.H{
			"title":   "EAGLE-FOX API",
			"version": "Golang 1.22",
			"access":  "El acceso es REST.",
		})
	})

	// Group routes for IoT Data with authentication middleware
	iotDataGroup := router.Group("/iotData", CORSMiddleware(), middlewares.AuthMiddleware())
	{
		iotDataGroup.GET("/", controllers.IotDataControllerIndex)
		iotDataGroup.POST("/", middlewares.AuthMiddleware(), controllers.IotDataControllerStore)
		iotDataGroup.GET("/:id", middlewares.AuthMiddleware(), controllers.IotDataControllerShow)
		iotDataGroup.PUT("/:id", middlewares.AuthMiddleware(), controllers.IotDataControllerUpdate)
		iotDataGroup.DELETE("/:id", middlewares.AuthMiddleware(), controllers.IotDataControllerDestroy)
	}

	iotDevicesGroup := router.Group("/iotDevices", CORSMiddleware(), middlewares.AuthMiddleware())
	{
		iotDevicesGroup.GET("/:id", controllers.IotDeviceControllerShow)
		iotDevicesGroup.GET("/", controllers.IotDeviceControllerIndex)
		iotDevicesGroup.POST("/", controllers.IotDeviceControllerStore)
		iotDevicesGroup.PUT("/:id", controllers.IotDeviceControllerUpdate)
		iotDevicesGroup.DELETE("/:id", controllers.IotDeviceControllerDestroy)
	}

	// Group routes for Users with authentication middleware
	userGroup := router.Group("/users", CORSMiddleware(), middlewares.AuthMiddleware())
	{
		userGroup.GET("/", controllers.UserControllerIndex)
		userGroup.POST("/", controllers.UserControllerStore)
		userGroup.GET("/:id", controllers.UserControllerShow)
		userGroup.PUT("/:id", controllers.UserControllerUpdate)
		userGroup.DELETE("/:id", controllers.UserControllerDestroy)
	}

	router.GET("/fix/myself", CORSMiddleware(), controllers.GetIotDevicesByMyself)
	router.OPTIONS("/fix/myself", CORSMiddleware(), controllers.GetIotDevicesByMyself)

	// Unprotected routes for user login
	router.POST("/users/login", CORSMiddleware(), controllers.UserControllerLogin)
	router.OPTIONS("/users/login", CORSMiddleware(), controllers.UserControllerLogin)

	// Return the configured router
	return router
}

func CORSMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "*")

		// alow OPTIONS
		if c.Request.Method == "OPTIONS" {
			c.AbortWithStatus(204)
			return
		}

		c.Next()
	}
}
