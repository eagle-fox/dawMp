package main

import (
	"github.com/eagle-fox/dawMp/controllers"
	"github.com/gin-gonic/gin"
)

func SetupRouter() *gin.Engine {
	router := gin.Default()

	router.LoadHTMLGlob("templates/*")

	router.GET("/", func(c *gin.Context) {
		c.HTML(200, "index.html", gin.H{
			"title":   "EAGLE-FOX API",
			"version": "Golang 1.22",
			"access":  "El acceso es REST.",
		})
	})

	iotDataGroup := router.Group("/iotData")
	{
		iotDataGroup.GET("/", controllers.IotDataControllerIndex)
		// iotDataGroup.POST("/", controllers.IotDataControllerStore)
		// iotDataGroup.GET("/:id", controllers.IotDataControllerShow)
		// iotDataGroup.PUT("/:id", controllers.IotDataControllerUpdate)
		// iotDataGroup.DELETE("/:id", controllers.IotDataControllerDestroy)
	}

	iotDeviceGroup := router.Group("/iotDevice")
	{
		iotDeviceGroup.GET("/", controllers.IotDeviceControllerIndex)
		iotDeviceGroup.POST("/", controllers.IotDeviceControllerStore)
		iotDeviceGroup.GET("/:id", controllers.IotDeviceControllerShow)
		iotDeviceGroup.PUT("/:id", controllers.IotDeviceControllerUpdate)
		iotDeviceGroup.DELETE("/:id", controllers.IotDeviceControllerDestroy)
	}

	return router
}
