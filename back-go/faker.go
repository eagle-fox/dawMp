package main

import (
	"back-go/models"
	"crypto/sha256"
	"encoding/hex"
	"github.com/go-faker/faker/v4"
	"github.com/google/uuid"
	"gorm.io/gorm"
	"log"
)

func generateFakeData() {

	// Disable default transaction
	models.DB = models.DB.Session(&gorm.Session{SkipDefaultTransaction: true})

	var demoPassword = "userpassword"
	var demoHashedPassword = getHashDemoPassword(demoPassword)

	if !checkDatabaseEmpty() {
		return
	}

	// Variables de configuración
	var numberUsers = 128
	var numberClientsPerUser = 8
	var numberIotDevicesPerClient = 32
	var numberIotDataPerDevice = 128

	totalOperations := numberUsers * (1 + numberClientsPerUser + (numberClientsPerUser * numberIotDevicesPerClient) + (numberClientsPerUser * numberIotDevicesPerClient * numberIotDataPerDevice))
	progress := make(chan int, totalOperations)

	log.Println("Generating fake data...")
	for i := 0; i < numberUsers; i++ {
		go func(i int) {
			err := models.DB.Transaction(func(tx *gorm.DB) error {
				log.Printf("Generating user %d", i)
				user := generateFakeUser(tx, demoHashedPassword)
				progress <- 1

				for j := 0; j < numberClientsPerUser; j++ {
					log.Printf("Generating client %d for user %d", j, i)
					client := generateFakeClient(tx, user.ID)
					progress <- 1

					for k := 0; k < numberIotDevicesPerClient; k++ {
						log.Printf("Generating IoT device %d for client %d", k, j)
						iotDevice := generateFakeIotDevice(tx, client.UserID)
						progress <- 1

						for l := 0; l < numberIotDataPerDevice; l++ {
							log.Printf("Generating IoT data %d for IoT device %d", l, k)
							generateFakeIotData(tx, iotDevice.ID)
							progress <- 1
						}
					}
				}
				return nil
			})
			if err != nil {
				log.Fatalf("Error during transaction: %v", err)
			}
		}(i)

	}

	models.DB = models.DB.Session(&gorm.Session{SkipDefaultTransaction: false})

}

func checkDatabaseEmpty() bool {
	var count int64
	models.DB.Model(&models.User{}).Count(&count)
	return count == 0
}

// getHashDemoPassword returns a string with a hashed password using SHA256
func getHashDemoPassword(password string) string {
	hash := sha256.Sum256([]byte(password))
	return hex.EncodeToString(hash[:])
}

func generateFakeUser(tx *gorm.DB, demoHashedPassword string) models.User {
	user := models.User{
		Nombre:          faker.Name(),
		ApellidoPrimero: faker.LastName(),
		ApellidoSegundo: faker.LastName(),
		Email:           faker.Email(),
		Password:        demoHashedPassword,
		Rol:             "ADMIN",
	}
	tx.Create(&user)
	return user
}

func generateFakeClient(tx *gorm.DB, userID uint) models.Client {
	client := models.Client{
		IPv4:   faker.IPv4(),
		Token:  uuid.New().String(),
		Locked: false,
		UserID: userID,
	}
	tx.Create(&client)
	return client
}

func generateFakeIotDevice(tx *gorm.DB, userID uint) models.IotDevice {
	iotDevice := models.IotDevice{
		Token:         uuid.New().String(),
		Name:          faker.Name(),
		Especie:       faker.Name(),
		Icon:          faker.Name(),
		User:          userID,
		LastLongitude: faker.Longitude(),
		LastLatitude:  faker.Latitude(),
	}
	tx.Create(&iotDevice)
	return iotDevice
}

func generateFakeIotData(tx *gorm.DB, deviceID uint) models.IotData {
	iotData := models.IotData{
		Device:    int(deviceID),
		Latitude:  faker.Latitude(),
		Longitude: faker.Longitude(),
	}
	tx.Create(&iotData)
	return iotData
}
