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
	iotDataGroup := router.Group("/iotData", middlewares.AuthMiddleware())
	{
		iotDataGroup.GET("/", controllers.IotDataControllerIndex)
		iotDataGroup.POST("/", controllers.IotDataControllerStore)
		iotDataGroup.GET("/:id", controllers.IotDataControllerShow)
		iotDataGroup.PUT("/:id", controllers.IotDataControllerUpdate)
		iotDataGroup.DELETE("/:id", controllers.IotDataControllerDestroy)
	}

	// Group routes for IoT Device with authentication middleware
	iotDeviceGroup := router.Group("/iotDevice", middlewares.AuthMiddleware())
	{
		iotDeviceGroup.GET("/", controllers.IotDeviceControllerIndex)
		iotDeviceGroup.POST("/", controllers.IotDeviceControllerStore)
		iotDeviceGroup.GET("/:id", controllers.IotDeviceControllerShow)
		iotDeviceGroup.PUT("/:id", controllers.IotDeviceControllerUpdate)
		iotDeviceGroup.DELETE("/:id", controllers.IotDeviceControllerDestroy)
	}

	// Group routes for Users with authentication middleware
	userGroup := router.Group("/users", middlewares.AuthMiddleware())
	{
		userGroup.GET("/", controllers.UserControllerIndex)
		userGroup.POST("/", controllers.UserControllerStore)
		userGroup.GET("/:id", controllers.UserControllerShow)
		userGroup.PUT("/:id", controllers.UserControllerUpdate)
		userGroup.DELETE("/:id", controllers.UserControllerDestroy)
	}

	router.POST("/users/login", CORSMiddleware(), controllers.UserControllerLogin)
	router.OPTIONS("/users/login", CORSMiddleware(), controllers.UserControllerLogin)

	// Return the configured router
	return router
}

func CORSMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Credentials", "true")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization, accept, origin, Cache-Control, X-Requested-With")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "POST, OPTIONS, GET, PUT, DELETE")

		if c.Request.Method == "OPTIONS" {
			c.AbortWithStatus(204)
			return
		}

		c.Next()
	}
}