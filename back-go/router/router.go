package router

import (
	"back-go/controllers"
	"back-go/middlewares"
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

	iotDataGroup := router.Group("/iotData", middlewares.AuthMiddleware())
	{
		iotDataGroup.GET("/", controllers.IotDataControllerIndex)
		iotDataGroup.POST("/", controllers.IotDataControllerStore)
		iotDataGroup.GET("/:id", controllers.IotDataControllerShow)
		iotDataGroup.PUT("/:id", controllers.IotDataControllerUpdate)
		iotDataGroup.DELETE("/:id", controllers.IotDataControllerDestroy)
	}

	iotDeviceGroup := router.Group("/iotDevice", middlewares.AuthMiddleware())
	{
		iotDeviceGroup.GET("/", controllers.IotDeviceControllerIndex)
		iotDeviceGroup.POST("/", controllers.IotDeviceControllerStore)
		iotDeviceGroup.GET("/:id", controllers.IotDeviceControllerShow)
		iotDeviceGroup.PUT("/:id", controllers.IotDeviceControllerUpdate)
		iotDeviceGroup.DELETE("/:id", controllers.IotDeviceControllerDestroy)
	}

	userGroup := router.Group("/users", middlewares.AuthMiddleware())
	{
		userGroup.GET("/", controllers.UserControllerIndex)
		userGroup.POST("/", controllers.UserControllerStore)
		userGroup.GET("/:id", controllers.UserControllerShow)
		userGroup.PUT("/:id", controllers.UserControllerUpdate)
		userGroup.DELETE("/:id", controllers.UserControllerDestroy)
		userGroup.POST("/login", controllers.UserControllerLogin)
	}

	return router
}
