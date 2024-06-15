// main package sets up the application, loads environment variables, initializes the database,
// and starts the server with necessary middlewares and routes.
package main

import (
	"back-go/middlewares"
	"back-go/models"
	"back-go/router"
	"fmt"
	"github.com/gin-gonic/gin"
	"github.com/joho/godotenv"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
	"log"
	"os"
)

// main is the entry point of the application. It loads environment variables,
// sets up the database connection, and starts the Gin server with middlewares and routes.
func main() {

	// Load .env file
	err := godotenv.Load()
	if err != nil {
		log.Fatalf("Error loading .env file")
	}

	// Get database connection details from environment variables
	DB_HOST := os.Getenv("DB_HOST")
	DB_PORT := os.Getenv("DB_PORT")
	DB_USER := os.Getenv("DB_USER")
	DB_PASSWORD := os.Getenv("DB_PASSWORD")
	DB_NAME := os.Getenv("DB_NAME")

	// Construct the DSN (Data Source Name) for connecting to the MySQL database
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local", DB_USER, DB_PASSWORD, DB_HOST, DB_PORT, DB_NAME)

	// Open the database connection
	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{
		Logger: logger.Default.LogMode(logger.Info),
	})

	// Automigrate the User model to keep the schema up-to-date
	db.AutoMigrate(&models.User{})

	if err != nil {
		log.Fatalf("failed to connect database: %v", err)
	}

	// Create a new Gin router
	r := gin.Default()

	// Use Gin's default Logger and Recovery middlewares
	r.Use(gin.Logger())
	r.Use(gin.Recovery())

	// Use custom authentication middleware
	r.Use(middlewares.AuthMiddleware())

	// Set the global database connection
	models.DB = db

	// Setup the routes
	router := router.SetupRouter()

	// Run the server on port 8080
	router.Run(":8080")
}
