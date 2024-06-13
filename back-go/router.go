package main

import "github.com/gin-gonic/gin"

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

	return router
}
