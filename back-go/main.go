package main

import (
	"back-go/middlewares"
	"back-go/models"
	"back-go/router"
	"back-go/types"
	"fmt"
	"github.com/gin-contrib/cors"
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
	initDb()
	initGinContext()
	initRouter()
}

func initDb() {
	log.Println("Starting server...")
	dbParams, err := loadEnv()
	if err != nil {
		log.Fatalf("Error loading environment variables: %v", err)
		return
	}
	db := dbConnect(dbParams)
	log.Println("Database connected")
	loadModels(db)
	doMigrations(db)
	generateFakeData()
}

func initGinContext() {
	r := gin.Default()
	initSecurity(r)
	initMiddleware(r)
}

func initRouter() {
	router := router.SetupRouter()
	router.Run(":2003")
}

func initSecurity(r *gin.Engine) gin.IRoutes {
	return r.Use(cors.Default())
}

func initMiddleware(r *gin.Engine) {
	r.Use(gin.Logger())                 // STD Logger
	r.Use(gin.Recovery())               // Recovery in case of panic
	r.Use(middlewares.AuthMiddleware()) // Our old PHP middleware migrated to Golang
}

func loadModels(db *gorm.DB) {
	log.Println("Loading models...")
	models.DB = db
	log.Println("Models loaded")
}

func doMigrations(db *gorm.DB) {
	log.Println("Running migrations...")
	db.AutoMigrate(
		&models.User{},
		&models.Client{},
		&models.IotDevice{},
		&models.IotData{},
	)
	log.Println("Migrations done")
}

func loadEnv() (*types.DbParams, error) {
	log.Println("Loading environment variables...")
	err := godotenv.Load()
	if err != nil {
		log.Fatalf("Error loading .env file")
		return nil, err
	}
	log.Println("Environment variables loaded")
	dbParams := &types.DbParams{
		Host:     os.Getenv("DB_HOST"),
		Port:     os.Getenv("DB_PORT"),
		User:     os.Getenv("DB_USER"),
		Password: os.Getenv("DB_PASSWORD"),
		Schema:   os.Getenv("DB_NAME"),
	}
	return dbParams, nil
}

func dbConnect(params *types.DbParams) *gorm.DB {
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s?charset=utf8mb4&parseTime=True&loc=Local", params.User, params.Password, params.Host, params.Port, params.Schema)
	log.Println("Connecting to database...")
	db, err := gorm.Open(mysql.Open(dsn), &gorm.Config{
		Logger: logger.Default.LogMode(logger.Info),
	})
	if err != nil {
		log.Fatalf("failed to connect database: %v", err)
	}
	log.Println("Database connected")
	return db
}
