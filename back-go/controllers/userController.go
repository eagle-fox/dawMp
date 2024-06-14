package controllers

import (
	"github.com/gin-gonic/gin"
	"net/http"
)

func UsersIndex(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "Users index"})
}

func UsersStore(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "Users store"})
}

func UsersLogin(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "Users login"})
}

func UsersShow(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "Users show"})
}

func UsersUpdate(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "Users update"})
}

func UsersDestroy(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "Users destroy"})
}

func UserLogin(c *gin.Context) {
	// Implement your logic here
	c.JSON(http.StatusOK, gin.H{"message": "User login"})
}
