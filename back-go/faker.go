package main

import (
	"back-go/models"
	"crypto/rand"
	"crypto/sha256"
	"encoding/hex"
	"github.com/go-faker/faker/v4"
	"github.com/google/uuid"
	"log"
	"math/big"
)

func generateFakeData() {

	var demoPassword = "userpassword"
	var demoHashedPassword = getHashDemoPassword(demoPassword)

	generateFakeUser(demoHashedPassword)
	generateFakeClient()

	if !checkDatabaseEmpty() {
		return
	}

	var numberUsers = 128
	var numberClients = 512
	var numberIotDevices = 2048
	var numberIotData = 10240

	log.Println("Generating fake data...")
	for i := 0; i < numberUsers; i++ {
		log.Printf("Generating user %d", i)
		generateFakeUser(demoHashedPassword)
	}

	for i := 0; i < numberClients; i++ {
		log.Printf("Generating client %d", i)
		generateFakeClient()
	}

	for i := 0; i < numberIotDevices; i++ {
		log.Printf("Generating IoT device %d", i)
		generateFakeIotDevice()
	}

	for i := 0; i < numberIotData; i++ {
		log.Printf("Generating IoT data %d", i)
		generateFakeIotData()
	}

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

func generateFakeUser(demoHashedPassword string) {
	var user models.User

	user.Nombre = faker.Name()
	user.ApellidoPrimero = faker.LastName()
	user.ApellidoSegundo = faker.LastName()
	user.Email = faker.Email()
	user.Password = demoHashedPassword
	user.Rol = "ADMIN"

	models.DB.Create(&user)
	return
}

func generateFakeClient() {
	var client models.Client

	client.IPv4 = faker.IPv4()
	client.Token = uuid.New().String()
	client.Locked = false
	client.UserID = getRandUserId()

	models.DB.Create(&client)
	return
}

func generateFakeIotDevice() {
	var iotDevice models.IotDevice

	iotDevice.Token = uuid.New().String()
	iotDevice.Name = faker.Name()
	iotDevice.Especie = faker.Name()
	iotDevice.Icon = faker.Name()
	iotDevice.User = int(getRandUserId())

	models.DB.Create(&iotDevice)
	return
}

func generateFakeIotData() {
	var iotData models.IotData

	iotData.Device = int(getRandIotDeviceId())
	iotData.Latitude = faker.Latitude()
	iotData.Longitude = faker.Longitude()

	models.DB.Create(&iotData)
	return
}

func getRandIotDeviceId() int {
	var iotDevice models.IotDevice
	var count int64
	models.DB.Model(&models.IotDevice{}).Count(&count)

	index, err := rand.Int(rand.Reader, big.NewInt(count))
	if err != nil {
		log.Fatalf("failed to generate random number: %v", err)
	}

	models.DB.First(&iotDevice, uint(index.Int64())+1)
	return int(iotDevice.ID)
}

// getRandUserId returns a random user ID from the database counting the range
// preventing broking the FK constraint
func getRandUserId() uint {
	var user models.User
	var count int64
	models.DB.Model(&models.User{}).Count(&count)

	index, err := rand.Int(rand.Reader, big.NewInt(count))
	if err != nil {
		log.Fatalf("failed to generate random number: %v", err)
	}

	models.DB.First(&user, uint(index.Int64())+1)
	return user.ID
}
